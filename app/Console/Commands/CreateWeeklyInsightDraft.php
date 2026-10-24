<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('insights:create-weekly-draft')]
#[Description('Create an editor-reviewed weekly investment education draft')]
class CreateWeeklyInsightDraft extends Command
{
    public function handle(): int
    {
        $week = now()->startOfWeek();
        $slug = 'weekly-investment-perspective-'.$week->format('Y-m-d');

        $article = Article::firstOrCreate(['slug' => $slug], [
            'title' => 'Weekly investment perspective: '.$week->format('j F Y'),
            'kind' => 'weekly_tip',
            'status' => 'draft',
            'excerpt' => 'Editorial draft awaiting research, compliance review and approval.',
            'body' => implode("\n\n", [
                'EDITORIAL BRIEF — DO NOT PUBLISH WITHOUT REVIEW',
                '1. Identify one durable investment principle relevant this week.',
                '2. Explain the principle without predicting returns or recommending a security.',
                '3. Add a practical research checklist and describe material risks.',
                '4. Verify every factual or time-sensitive claim against primary sources.',
                '5. End with the standard educational-information disclaimer.',
            ]),
            'scheduled_for' => $week->copy()->addDays(4)->setTime(9, 0),
            'generated_by_ai' => false,
        ]);

        $this->info(($article->wasRecentlyCreated ? 'Created' : 'Already exists').': '.Str::limit($article->title, 80));

        return self::SUCCESS;
    }
}
