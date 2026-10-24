<?php

namespace Tests\Feature;

use App\Enums\PageStatus;
use App\Models\Article;
use App\Models\Page;
use Database\Seeders\ConsultancyContentSeeder;
use Database\Seeders\CoreContentSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ConsultancySiteTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @var list<string>
     */
    private const CONSULTANCY_SLUGS = [
        'services', 'business-strategy', 'data-analytics', 'ai-advisory', 'financial-analytics',
        'about', 'industries', 'approach', 'engagement-models', 'contact', 'consultation',
        'platform', 'ai-tools', 'faq', 'confidentiality',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([CoreContentSeeder::class, ConsultancyContentSeeder::class]);
    }

    public function test_the_home_page_presents_the_consultancy_and_links_every_service_line(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Business &amp; analytics consultancy', false);
        $response->assertSee('Decisions you can defend. Analytics you can trust.');

        foreach (['business-strategy', 'data-analytics', 'ai-advisory', 'financial-analytics'] as $slug) {
            $response->assertSee(route('pages.show', $slug), false);
        }
    }

    public function test_every_consultancy_page_is_published_and_renders(): void
    {
        foreach (self::CONSULTANCY_SLUGS as $slug) {
            $this->get('/'.$slug)->assertOk();
        }
    }

    public function test_seeded_content_never_claims_regulatory_authorisation_or_performance(): void
    {
        $content = Page::query()->where('status', PageStatus::Published)->pluck('blocks')->toJson();

        foreach (['FCA authorised', 'FCA-authorised', 'regulated by the FCA', 'guaranteed returns', 'guaranteed savings'] as $forbidden) {
            $this->assertStringNotContainsStringIgnoringCase($forbidden, $content);
        }
    }

    public function test_the_platform_page_discloses_its_development_status_and_offers_the_waitlist(): void
    {
        $response = $this->get('/platform');

        $response->assertOk();
        $response->assertSee('the platform is not yet available', false);
        $response->assertSee(route('waitlist.store'), false);
        $response->assertSee('Capital is at risk');
    }

    public function test_confidentiality_is_covered_on_its_own_page_and_linked_from_home_and_footer(): void
    {
        $this->get('/confidentiality')
            ->assertOk()
            ->assertSee('Confidentiality terms first')
            ->assertSee('Please keep sensitive details out of web forms.');

        $this->get('/')->assertSee('Confidentiality comes first.')->assertSee(route('pages.show', 'confidentiality'), false);
    }

    public function test_the_ai_tools_page_renders_both_interactive_tools(): void
    {
        $response = $this->get('/ai-tools');

        $response->assertOk();
        $response->assertSee('data-readiness', false);
        $response->assertSee('data-estimator', false);
        $response->assertSee('Analytics and AI readiness check');
    }

    public function test_faq_pages_include_structured_data(): void
    {
        $response = $this->get('/faq');

        $response->assertOk();
        $response->assertSee('"@type":"FAQPage"', false);
        $response->assertSee('What kinds of organisations do you work with?');
    }

    public function test_the_header_dropdown_and_footer_link_to_the_service_pages(): void
    {
        $response = $this->get('/services');

        $response->assertOk();
        $response->assertSee('aria-label="Footer navigation"', false);
        $response->assertSee('Financial &amp; Investment Analytics', false);
        $response->assertSee('aria-current="page"', false);
    }

    public function test_the_home_page_shows_only_published_insights(): void
    {
        Article::query()->create([
            'title' => 'Unreviewed draft idea', 'slug' => 'unreviewed-draft-idea', 'kind' => 'weekly_tip',
            'status' => 'draft', 'excerpt' => 'Not for publication', 'body' => 'Draft body',
        ]);
        Article::query()->create([
            'title' => 'A reviewed perspective', 'slug' => 'a-reviewed-perspective', 'kind' => 'weekly_tip',
            'status' => 'published', 'excerpt' => 'Ready to read', 'body' => 'Body',
            'reviewed_at' => now(), 'published_at' => now()->subMinute(),
        ]);

        $response = $this->get('/');

        $response->assertSee('A reviewed perspective');
        $response->assertDontSee('Unreviewed draft idea');
    }

    public function test_the_consultation_form_preselects_the_consulting_topic(): void
    {
        $this->get('/consultation')->assertOk()->assertSee('<option value="consulting" selected>', false);
        $this->get('/consultation?topic=platform')->assertSee('<option value="platform" selected>', false);
    }

    public function test_consulting_enquiries_are_accepted_by_both_forms(): void
    {
        $this->post('/consultation', [
            'name' => 'Sam Example', 'email' => 'sam@example.com', 'preferred_contact' => 'email',
            'topic' => 'consulting', 'consent' => '1',
        ])->assertSessionHasNoErrors();

        $this->post('/contact', [
            'name' => 'Sam Example', 'email' => 'sam@example.com',
            'enquiry_type' => 'consulting', 'message' => 'We would like help with reporting.',
        ])->assertSessionHasNoErrors();
    }

    public function test_the_sitemap_lists_published_pages_and_articles_only(): void
    {
        Page::factory()->create(['slug' => 'secret-draft']);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee(route('pages.show', 'services'), false);
        $response->assertDontSee('secret-draft');
    }
}
