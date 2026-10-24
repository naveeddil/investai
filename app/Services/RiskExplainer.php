<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Asks Claude to explain, in plain English, the output of RiskEstimator.
 *
 * Only percentages and the computed figures are sent: no names, e-mail
 * addresses or monetary amounts.
 */
class RiskExplainer
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
You are an educational writing assistant on the website of InvestAI, a business and analytics consultancy. A visitor has used a simple, illustrative portfolio risk calculator, and you will explain its output in plain English for a non-expert.

Rules:
- Use only the figures provided. Do not invent or adjust numbers, and do not predict future returns.
- Do not recommend buying, selling or holding any product, and do not suggest a specific allocation or provider. Do not tell the reader what they should do.
- The assumptions are illustrative round numbers, and the calculation treats returns as normally distributed, which understates extreme losses. Say so briefly.
- Be calm and balanced. Do not reassure or alarm beyond what the figures support.
- Write 220 to 300 words in British English, in exactly three sections whose headings each start on their own line with "## ": "## What the numbers say", "## What could go wrong", "## Questions worth asking".
- In the last section give three or four questions the reader could consider or put to a qualified adviser.
- End with the single sentence: "This is general education, not personal financial advice."
- Plain text only: no tables, bullet symbols other than a leading "- " on questions, and no other markdown.
PROMPT;

    /**
     * @param  array<string, float|int>  $allocation
     * @param  array<string, mixed>  $metrics  Output of RiskEstimator::estimate().
     *
     * @throws RuntimeException When the provider is unavailable or returns nothing usable.
     */
    public function explain(array $allocation, int $years, int $tolerancePercent, array $metrics): string
    {
        $apiKey = (string) config('services.anthropic.key');

        if ($apiKey === '') {
            throw new RuntimeException('The AI explanation is not configured.');
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'anthropic-beta' => 'server-side-fallback-2026-07-01',
            ])
                ->acceptJson()
                ->asJson()
                ->timeout((int) config('services.anthropic.timeout', 45))
                ->post(rtrim((string) config('services.anthropic.base_url'), '/').'/v1/messages', [
                    'model' => config('services.anthropic.model'),
                    'max_tokens' => 4000,
                    'output_config' => ['effort' => 'low'],
                    'fallbacks' => 'default',
                    'system' => self::SYSTEM_PROMPT,
                    'messages' => [['role' => 'user', 'content' => $this->describe($allocation, $years, $tolerancePercent, $metrics)]],
                ])
                ->throw()
                ->json();
        } catch (ConnectionException|RequestException $exception) {
            report($exception);

            throw new RuntimeException('The AI provider could not be reached.', previous: $exception);
        }

        if (($response['stop_reason'] ?? null) === 'refusal') {
            throw new RuntimeException('The AI provider declined to explain this request.');
        }

        $text = collect($response['content'] ?? [])
            ->where('type', 'text')
            ->pluck('text')
            ->implode("\n");

        if (trim($text) === '') {
            throw new RuntimeException('The AI provider returned an empty explanation.');
        }

        return trim($text);
    }

    /**
     * @param  array<string, float|int>  $allocation
     * @param  array<string, mixed>  $metrics
     */
    private function describe(array $allocation, int $years, int $tolerancePercent, array $metrics): string
    {
        $classes = RiskEstimator::assumptions()['classes'];

        $lines = ['Allocation (percent of portfolio):'];

        foreach ($classes as $key => $class) {
            $lines[] = sprintf('- %s: %s%% (assumed return %s%% a year, assumed volatility %s%%)', $class['label'], rtrim(rtrim(number_format((float) ($allocation[$key] ?? 0), 1), '0'), '.'), $class['expectedReturn'], $class['volatility']);
        }

        $lines[] = sprintf('Time horizon: %d years', $years);
        $lines[] = sprintf('Stated tolerance for a single bad year: a fall of up to %d%%', $tolerancePercent);
        $lines[] = 'Calculated figures:';
        $lines[] = sprintf('- Assumed average yearly return: %s%%', $metrics['expectedReturn']);
        $lines[] = sprintf('- Typical yearly swing (volatility): %s%% (risk band: %s)', $metrics['volatility'], $metrics['band']);
        $lines[] = sprintf('- A bad year (about 1 in 20): %s%%', $metrics['badYear']);
        $lines[] = sprintf('- A very bad year (about 1 in 100): %s%%', $metrics['veryBadYear']);
        $lines[] = sprintf('- After %d years, value as a multiple of the amount invested: low case %s, middle %s, high case %s', $years, $metrics['lowMultiple'], $metrics['medianMultiple'], $metrics['highMultiple']);
        $lines[] = sprintf('- Largest single holding: %s%%; share in growth assets: %s%%; diversification ratio: %s', $metrics['largestHolding'], $metrics['growthShare'], $metrics['diversificationRatio']);
        $lines[] = sprintf('- Bad-year loss compared with stated tolerance: %s', $metrics['toleranceStatus']);

        foreach ($metrics['flags'] as $flag) {
            $lines[] = '- Flag: '.$flag;
        }

        return implode("\n", $lines);
    }
}
