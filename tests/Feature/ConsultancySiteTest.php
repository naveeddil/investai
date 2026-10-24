<?php

namespace Tests\Feature;

use App\Enums\PageStatus;
use App\Models\Article;
use App\Models\Page;
use Database\Seeders\ConsultancyContentSeeder;
use Database\Seeders\CoreContentSeeder;
use Database\Seeders\ProductWebsiteSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ConsultancySiteTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @var list<string>
     */
    private const PRODUCT_SLUGS = [
        'about', 'approach', 'contact', 'consultation', 'platform', 'ai-tools', 'faq', 'confidentiality',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([CoreContentSeeder::class, ConsultancyContentSeeder::class, ProductWebsiteSeeder::class]);
    }

    public function test_the_home_page_presents_the_product_and_links_the_core_journey(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Invest smarter with AI');
        $response->assertSee('Investment research that starts with you.');

        foreach (['platform', 'ai-tools', 'about'] as $slug) {
            $response->assertSee(route('pages.show', $slug), false);
        }
    }

    public function test_every_core_product_page_is_published_and_renders(): void
    {
        foreach (self::PRODUCT_SLUGS as $slug) {
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

    public function test_the_platform_page_presents_its_track_record_and_offers_consultation(): void
    {
        $response = $this->get('/platform');

        $response->assertOk();
        $response->assertSee('Nearly three years of focused development');
        $response->assertSee(route('consultation.store'), false);
        $response->assertSee('Regulatory clarity remains essential.');
    }

    public function test_confidentiality_is_covered_on_its_own_page_and_linked_from_home_and_footer(): void
    {
        $this->get('/confidentiality')
            ->assertOk()
            ->assertSee('Confidentiality terms first')
            ->assertSee('Please keep sensitive details out of web forms.');

        $this->get('/')->assertSee(route('pages.show', 'confidentiality'), false);
    }

    public function test_the_ai_tools_page_renders_both_investment_tools_in_pounds(): void
    {
        $response = $this->get('/ai-tools');

        $response->assertOk();
        $response->assertSee('data-risk', false);
        $response->assertSee('data-investment-calculator', false);
        $response->assertSee('Investment growth &amp; fee calculator', false);
        $response->assertSee('Starting amount (£)');
    }

    public function test_faq_pages_include_structured_data(): void
    {
        $response = $this->get('/faq');

        $response->assertOk();
        $response->assertSee('"@type":"FAQPage"', false);
        $response->assertSee('How established is InvestAI?');
    }

    public function test_legacy_service_pages_redirect_to_the_consolidated_product_pages(): void
    {
        $this->get('/services')->assertRedirect('/platform');
        $this->get('/financial-analytics')->assertRedirect('/ai-tools');
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

    public function test_the_consultation_steps_show_their_pictures(): void
    {
        $response = $this->get('/consultation')->assertOk();

        foreach (['request-desk', 'conversation-meeting', 'proposal-writing'] as $image) {
            $response->assertSee("/images/editorial/{$image}.jpg", false);
            $this->assertFileExists(public_path("images/editorial/{$image}.jpg"));
        }
    }

    public function test_the_step_pictures_migration_adds_images_without_the_seeder(): void
    {
        $page = Page::query()->where('slug', 'consultation')->firstOrFail();
        $page->update(['blocks' => collect($page->blocks)->map(function (array $block): array {
            foreach ($block['data']['steps'] ?? [] as $index => $step) {
                unset($block['data']['steps'][$index]['image'], $block['data']['steps'][$index]['image_alt']);
            }

            return $block;
        })->all()]);
        $this->get('/consultation')->assertDontSee('/images/editorial/request-desk.jpg', false);

        (require database_path('migrations/2026_10_24_150000_add_images_to_consultation_process_steps.php'))->up();

        $this->get('/consultation')->assertSee('/images/editorial/request-desk.jpg', false);
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
        $response->assertSee(route('pages.show', 'platform'), false);
        $response->assertDontSee('secret-draft');
    }
}
