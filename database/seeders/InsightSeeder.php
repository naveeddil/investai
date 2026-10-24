<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class InsightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Start with your time horizon, not the market headline',
                'slug' => 'start-with-your-time-horizon',
                'excerpt' => 'A practical framework for matching investment decisions to when the money may be needed.',
                'body' => "Market headlines are immediate; investment goals usually are not. Before reacting to daily price movements, define when the money may be needed, how flexible that date is, and what loss you could tolerate without abandoning the plan.\n\nA shorter horizon generally calls for greater emphasis on stability and liquidity. A longer horizon may allow more short-term fluctuation, but it does not remove risk. Revisit the horizon whenever your goals or circumstances change.\n\nThis is general educational information, not personal financial advice or a recommendation to buy or sell any investment.",
                'featured_image' => 'images/editorial/glass-architecture.jpg',
                'featured_image_alt' => 'Geometric glass architecture representing a structured investment plan',
            ],
            [
                'title' => 'Diversification is about different risks—not just more holdings',
                'slug' => 'diversification-is-about-different-risks',
                'excerpt' => 'Why counting investments is less useful than understanding what can cause them to move together.',
                'body' => "Owning many investments does not automatically create meaningful diversification. Several holdings can still depend on the same sector, currency, economic outcome, or market sentiment.\n\nA useful review asks what could make each holding lose value and whether those drivers overlap. Diversification cannot prevent losses, but spreading exposure across genuinely different risk sources can reduce dependence on a single outcome.\n\nThis is general educational information, not personal financial advice or a recommendation to buy or sell any investment.",
                'featured_image' => 'images/editorial/reflected-architecture.jpg',
                'featured_image_alt' => 'Reflected buildings illustrating different perspectives and risk exposures',
            ],
            [
                'title' => 'Small fees compound too',
                'slug' => 'small-fees-compound-too',
                'excerpt' => 'A reminder to compare total costs alongside risk, service, and expected long-term value.',
                'body' => "Investment costs can appear modest when expressed as annual percentages, yet they are deducted repeatedly and reduce the capital that remains invested. Over long periods, that difference can compound.\n\nCompare the full cost: product charges, platform or advisory fees, transaction costs, taxes where relevant, and any exit restrictions. The cheapest option is not automatically the best, but costs should be transparent and justified by the value received.\n\nThis is general educational information, not personal financial advice or a recommendation to buy or sell any investment.",
                'featured_image' => 'images/editorial/financial-district.jpg',
                'featured_image_alt' => 'Financial district buildings representing long-term investment costs',
            ],
        ];

        foreach ($articles as $article) {
            Article::query()->updateOrCreate(
                ['slug' => $article['slug']],
                [...$article, 'kind' => 'weekly_tip', 'status' => 'published', 'generated_by_ai' => false, 'review_notes' => 'Initial educational content reviewed for staging.', 'reviewed_at' => now(), 'published_at' => now()],
            );
        }
    }
}
