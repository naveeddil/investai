<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContentAutomationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_weekly_command_is_idempotent_and_only_creates_a_draft(): void
    {
        $this->artisan('insights:create-weekly-draft')->assertSuccessful();
        $this->artisan('insights:create-weekly-draft')->assertSuccessful();

        $this->assertDatabaseCount(Article::class, 1);
        $this->assertDatabaseHas(Article::class, [
            'kind' => 'weekly_tip',
            'status' => 'draft',
            'generated_by_ai' => false,
        ]);
    }

    public function test_wordpress_posts_are_sanitized_and_imported_as_drafts(): void
    {
        Http::fake([
            'https://legacy.example/wp-json/wp/v2/posts*' => Http::response([[
                'title' => ['rendered' => 'Legacy &amp; Post'],
                'slug' => 'legacy-post',
                'link' => 'https://legacy.example/legacy-post/',
                'content' => ['rendered' => '<style>bad</style><p>Useful text</p><script>alert(1)</script>'],
            ]]),
        ]);

        $this->artisan('content:import-wordpress', ['--url' => 'https://legacy.example'])->assertSuccessful();

        $article = Article::firstOrFail();
        $this->assertSame('draft', $article->status);
        $this->assertSame('legacy', $article->kind);
        $this->assertSame('Useful text', $article->body);
        $this->assertNull($article->reviewed_at);
        $this->assertNull($article->published_at);
    }
}
