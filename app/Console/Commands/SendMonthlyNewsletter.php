<?php

namespace App\Console\Commands;

use App\Mail\MonthlyNewsletterMail;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMonthlyNewsletter extends Command
{
    protected $signature = 'newsletter:send-monthly';

    protected $description = 'Send the previous month’s published InvestAI articles to monthly subscribers';

    public function handle(): int
    {
        $month = now()->subMonthNoOverflow()->startOfMonth();
        $campaign = 'monthly-'.$month->format('Y-m');
        if (SiteSetting::read('newsletter.last_monthly_campaign') === $campaign) {
            $this->info('Monthly newsletter already sent.');

            return self::SUCCESS;
        }
        $articles = Article::query()->published()->whereBetween('published_at', [$month, $month->copy()->endOfMonth()])->latest('published_at')->get();
        if ($articles->isEmpty()) {
            $this->info('No published articles for '.$month->format('F Y').'.');

            return self::SUCCESS;
        }
        NewsletterSubscriber::query()->active()->where('monthly_newsletter', true)->each(fn (NewsletterSubscriber $subscriber) => Mail::to($subscriber->email)->send(new MonthlyNewsletterMail($subscriber, $articles, $month->format('F Y'))));
        SiteSetting::query()->updateOrCreate(['key' => 'newsletter.last_monthly_campaign'], ['group' => 'newsletter', 'value' => $campaign, 'type' => 'string']);
        $this->info('Monthly newsletter sent for '.$month->format('F Y').'.');

        return self::SUCCESS;
    }
}
