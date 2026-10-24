<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InsightPublishingTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_only_reviewed_published_articles_are_public(): void
    {
        $published = Article::factory()->create([
            'slug' => 'reviewed-insight',
            'status' => 'published',
            'reviewed_at' => now(),
            'published_at' => now()->subMinute(),
        ]);
        Article::factory()->create(['slug' => 'private-draft']);

        $this->get('/insights')
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee('private-draft');

        $this->get('/insights/reviewed-insight')->assertOk();
        $this->get('/insights/private-draft')->assertNotFound();
    }

    public function test_an_article_cannot_be_published_without_review_and_publication_dates(): void
    {
        $this->expectException(ValidationException::class);

        Article::factory()->create(['status' => 'published']);
    }
}
