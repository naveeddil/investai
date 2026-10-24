<?php

namespace Tests\Feature;

use App\Models\Article;
use Database\Seeders\ResearchTipsSeeder;
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

    public function test_research_tip_articles_are_substantial_and_include_practical_guidance(): void
    {
        $this->seed(ResearchTipsSeeder::class);

        $slugs = [
            'main-reasons-to-explain-fast-business-builder',
            'we-deal-with-best-business-managment',
            '7-step-social-media-marketing-strategy',
            'success-is-not-final-failure-is-not-fatal',
            'uplifting-the-market-value-of-finance',
            'turn-market-headlines-into-research-questions',
            'small-investment-fees-long-term-gap',
            'seven-questions-before-taking-more-investment-risk',
            'how-ai-can-improve-investment-research',
        ];

        $this->assertSame(count($slugs), Article::query()->whereIn('slug', $slugs)->count());

        foreach ($slugs as $slug) {
            $article = Article::query()->where('slug', $slug)->firstOrFail();

            $this->assertGreaterThan(1_000, strlen($article->body));
            $this->assertStringContainsString('Research tip', $article->body);
            $this->get('/insights/'.$slug)->assertOk()->assertSee('Research tip');
        }
    }
}
