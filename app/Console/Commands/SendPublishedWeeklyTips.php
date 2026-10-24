<?php

namespace App\Console\Commands;

use App\Mail\WeeklyTipMail;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPublishedWeeklyTips extends Command
{
    protected $signature = 'newsletter:send-weekly-tips';

    protected $description = 'Email newly published, reviewed weekly tips to confirmed subscribers';

    public function handle(): int
    {
        $articles = Article::query()->published()->where('kind', 'weekly_tip')->whereNull('weekly_emailed_at')->oldest('published_at')->get();
        foreach ($articles as $article) {
            NewsletterSubscriber::query()->active()->where('weekly_tips', true)->each(fn (NewsletterSubscriber $subscriber) => Mail::to($subscriber->email)->send(new WeeklyTipMail($subscriber, $article)));
            $article->update(['weekly_emailed_at' => now()]);
            $this->info('Sent: '.$article->title);
        }

        return self::SUCCESS;
    }
}
