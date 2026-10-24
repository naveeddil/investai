<?php

namespace Tests\Feature;

use App\Services\RiskEstimator;
use Database\Seeders\ConsultancyContentSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RiskCalculatorTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @var array<string, int>
     */
    private const BALANCED = ['equities' => 50, 'bonds' => 30, 'cash' => 10, 'property' => 5, 'alternatives' => 5];

    public function test_an_all_cash_mix_has_the_lowest_risk(): void
    {
        $metrics = RiskEstimator::estimate(['cash' => 100], 5, 10);

        $this->assertSame(2.0, $metrics['expectedReturn']);
        $this->assertSame(0.5, $metrics['volatility']);
        $this->assertSame(1.18, $metrics['badYear']);
        $this->assertSame('Low', $metrics['band']);
        $this->assertSame('within', $metrics['toleranceStatus']);
    }

    public function test_an_all_equity_mix_reproduces_the_hand_calculation(): void
    {
        $metrics = RiskEstimator::estimate(['equities' => 100], 10, 15);

        $this->assertSame(6.5, $metrics['expectedReturn']);
        $this->assertSame(16.0, $metrics['volatility']);
        $this->assertSame(-19.82, $metrics['badYear']);
        $this->assertSame(-30.72, $metrics['veryBadYear']);
        $this->assertSame('High', $metrics['band']);
        $this->assertSame('above', $metrics['toleranceStatus']);
        $this->assertContains('Concentrated: more than 60% sits in a single asset class.', $metrics['flags']);
        $this->assertSame('close', RiskEstimator::estimate(['equities' => 100], 10, 18)['toleranceStatus']);
        $this->assertSame('within', RiskEstimator::estimate(['equities' => 100], 10, 20)['toleranceStatus']);
    }

    public function test_diversification_lowers_volatility_below_the_weighted_average(): void
    {
        $metrics = RiskEstimator::estimate(self::BALANCED, 10, 15);

        $this->assertGreaterThan(1.0, $metrics['diversificationRatio']);
        $this->assertLessThan(0.5 * 16 + 0.3 * 6 + 0.1 * 0.5 + 0.05 * 13 + 0.05 * 15, $metrics['volatility']);
        $this->assertGreaterThan($metrics['medianMultiple'], $metrics['highMultiple']);
        $this->assertGreaterThan($metrics['lowMultiple'], $metrics['medianMultiple']);
    }

    public function test_the_calculator_renders_on_the_tools_page_with_ai_switched_off_by_default(): void
    {
        $this->seed(ConsultancyContentSeeder::class);
        config(['services.anthropic.key' => null]);

        $response = $this->get('/ai-tools');

        $response->assertOk();
        $response->assertSee('Financial risk calculator');
        $response->assertSee('data-risk', false);
        $response->assertSee('Without AI');
        $response->assertSee('AI explanations are not switched on for this site yet.');
        $response->assertDontSee('data-ai-button', false);
    }

    public function test_ai_controls_appear_when_an_api_key_is_configured(): void
    {
        $this->seed(ConsultancyContentSeeder::class);
        config(['services.anthropic.key' => 'test-key']);

        $this->get('/ai-tools')->assertOk()->assertSee('data-ai-button', false)->assertSee('processed by an external AI service');
    }

    public function test_the_explanation_endpoint_is_unavailable_without_an_api_key(): void
    {
        config(['services.anthropic.key' => null]);
        Http::fake();

        $this->postJson(route('tools.risk-explanation'), $this->payload())->assertStatus(503);

        Http::assertNothingSent();
    }

    public function test_the_explanation_endpoint_returns_text_and_sends_only_numbers_to_the_provider(): void
    {
        config(['services.anthropic.key' => 'test-key', 'services.anthropic.model' => 'claude-opus-5-5']);
        Http::fake(['api.anthropic.com/*' => Http::response([
            'stop_reason' => 'end_turn',
            'content' => [
                ['type' => 'thinking', 'thinking' => ''],
                ['type' => 'text', 'text' => "## What the numbers say\nA balanced mix."],
            ],
        ])]);

        $response = $this->postJson(route('tools.risk-explanation'), $this->payload());

        $response->assertOk()->assertJson(['explanation' => "## What the numbers say\nA balanced mix."]);

        Http::assertSent(function (Request $request): bool {
            $body = $request->data();
            $prompt = $body['messages'][0]['content'];

            return $request->url() === 'https://api.anthropic.com/v1/messages'
                && $request->hasHeader('x-api-key', 'test-key')
                && $request->hasHeader('anthropic-version', '2023-06-01')
                && $body['model'] === 'claude-opus-5-5'
                && str_contains($body['system'], 'not personal financial advice')
                && str_contains($prompt, 'Time horizon: 10 years')
                && str_contains($prompt, 'A bad year (about 1 in 20)')
                && ! str_contains($prompt, '@');
        });
    }

    public function test_explanations_are_rate_limited_per_visitor(): void
    {
        config(['services.anthropic.key' => 'test-key']);
        Http::fake(['api.anthropic.com/*' => Http::response(['stop_reason' => 'end_turn', 'content' => [['type' => 'text', 'text' => 'Fine.']]])]);

        foreach (range(1, 3) as $attempt) {
            $this->postJson(route('tools.risk-explanation'), $this->payload())->assertOk();
        }

        $this->postJson(route('tools.risk-explanation'), $this->payload())->assertStatus(429);
    }

    public function test_the_explanation_request_is_validated(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);
        config(['services.anthropic.key' => 'test-key']);
        Http::fake();

        $this->postJson(route('tools.risk-explanation'), [...$this->payload(), 'consent' => false])->assertJsonValidationErrors('consent');
        $this->postJson(route('tools.risk-explanation'), [...$this->payload(), 'allocation' => [...self::BALANCED, 'cash' => 40]])->assertJsonValidationErrors('allocation');
        $this->postJson(route('tools.risk-explanation'), [...$this->payload(), 'years' => 0])->assertJsonValidationErrors('years');
        $this->postJson(route('tools.risk-explanation'), [...$this->payload(), 'tolerance' => 12])->assertJsonValidationErrors('tolerance');
        $this->postJson(route('tools.risk-explanation'), [...$this->payload(), 'allocation' => [...self::BALANCED, 'name' => 'x']])->assertJsonValidationErrors('allocation');

        Http::assertNothingSent();
    }

    public function test_provider_failures_and_refusals_return_a_friendly_error(): void
    {
        config(['services.anthropic.key' => 'test-key']);

        Http::fake(['api.anthropic.com/*' => Http::response(['error' => 'overloaded'], 529)]);
        $this->postJson(route('tools.risk-explanation'), $this->payload())->assertStatus(502)->assertJsonPath('message', fn (string $message): bool => str_contains($message, 'calculation above still stands'));

        Http::fake(['api.anthropic.com/*' => Http::response(['stop_reason' => 'refusal', 'content' => []])]);
        $this->postJson(route('tools.risk-explanation'), $this->payload())->assertStatus(502);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return ['allocation' => self::BALANCED, 'years' => 10, 'tolerance' => 15, 'consent' => true];
    }
}
