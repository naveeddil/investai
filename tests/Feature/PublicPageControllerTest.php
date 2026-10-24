<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PublicPageControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_published_page_is_publicly_visible_and_escapes_content(): void
    {
        Page::factory()->published()->create([
            'slug' => 'research',
            'title' => 'Research',
            'blocks' => [[
                'type' => 'rich_text',
                'data' => ['heading' => 'Research', 'text' => '<script>alert("xss")</script>', 'background' => 'light'],
            ]],
        ]);

        $response = $this->get('/research');

        $response->assertOk();
        $response->assertSee('Research');
        $response->assertDontSee('<script>alert("xss")</script>', false);
    }

    public function test_draft_page_is_not_publicly_accessible(): void
    {
        Page::factory()->create(['slug' => 'private-draft']);

        $this->get('/private-draft')->assertNotFound();
    }

    public function test_public_page_uses_the_configured_site_theme(): void
    {
        Page::factory()->published()->create(['slug' => 'themed-page']);
        SiteSetting::factory()->create([
            'group' => 'appearance',
            'key' => 'appearance.theme',
            'type' => 'string',
            'value' => 'ivory',
        ]);

        $response = $this->get('/themed-page');

        $response->assertOk();
        $response->assertSee('data-theme="ivory"', false);
    }

    public function test_public_page_falls_back_when_the_configured_theme_is_invalid(): void
    {
        Page::factory()->published()->create(['slug' => 'safe-theme']);
        SiteSetting::factory()->create([
            'group' => 'appearance',
            'key' => 'appearance.theme',
            'type' => 'string',
            'value' => 'untrusted-class',
        ]);

        $response = $this->get('/safe-theme');

        $response->assertOk();
        $response->assertSee('data-theme="midnight"', false);
        $response->assertDontSee('data-theme="untrusted-class"', false);
    }

    public function test_current_navigation_link_is_identified_and_visually_active(): void
    {
        Page::factory()->published()->create(['slug' => 'about', 'title' => 'About InvestAI']);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('aria-current="page"', false);
        $response->assertSee('after:scale-x-100', false);
    }
}
