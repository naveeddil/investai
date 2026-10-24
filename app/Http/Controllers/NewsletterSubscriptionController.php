<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmNewsletterSubscription;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['nullable', 'string', 'max:120'], 'email' => ['required', 'email:rfc', 'max:255'], 'monthly_newsletter' => ['nullable', 'boolean', 'required_without:weekly_tips'], 'weekly_tips' => ['nullable', 'boolean', 'required_without:monthly_newsletter'], 'consent' => ['accepted']]);
        $confirmationToken = Str::random(64);
        $manageToken = Str::random(64);
        $subscriber = NewsletterSubscriber::query()->updateOrCreate(['email' => Str::lower($data['email'])], ['name' => $data['name'] ?? null, 'monthly_newsletter' => $request->boolean('monthly_newsletter'), 'weekly_tips' => $request->boolean('weekly_tips'), 'status' => 'pending', 'confirmation_token_hash' => hash('sha256', $confirmationToken), 'manage_token_hash' => hash('sha256', $manageToken), 'manage_token' => $manageToken, 'consent_version' => 'newsletter-v1', 'consented_at' => now(), 'confirmed_at' => null, 'unsubscribed_at' => null]);
        Mail::to($subscriber->email)->send(new ConfirmNewsletterSubscription($subscriber, $confirmationToken, $manageToken));

        return back()->with('newsletter_success', 'Please check your email and confirm your subscription.');
    }

    public function confirm(string $token): View
    {
        $subscriber = NewsletterSubscriber::query()->where('confirmation_token_hash', hash('sha256', $token))->firstOrFail();
        $subscriber->update(['status' => 'active', 'confirmed_at' => now(), 'confirmation_token_hash' => null]);

        return view('newsletter.status', ['heading' => 'Subscription confirmed', 'message' => 'You will now receive the InvestAI emails you selected.']);
    }

    public function preferences(string $token): View
    {
        return view('newsletter.preferences', ['subscriber' => $this->find($token), 'token' => $token]);
    }

    public function update(Request $request, string $token): RedirectResponse
    {
        $data = $request->validate(['name' => ['nullable', 'string', 'max:120'], 'monthly_newsletter' => ['nullable', 'boolean', 'required_without:weekly_tips'], 'weekly_tips' => ['nullable', 'boolean', 'required_without:monthly_newsletter']]);
        $this->find($token)->update(['name' => $data['name'] ?? null, 'monthly_newsletter' => $request->boolean('monthly_newsletter'), 'weekly_tips' => $request->boolean('weekly_tips')]);

        return back()->with('newsletter_preferences_saved', 'Your email preferences have been updated.');
    }

    public function unsubscribe(string $token): View
    {
        $this->find($token)->update(['status' => 'unsubscribed', 'monthly_newsletter' => false, 'weekly_tips' => false, 'unsubscribed_at' => now()]);

        return view('newsletter.status', ['heading' => 'You are unsubscribed', 'message' => 'InvestAI will no longer send newsletter or weekly-tip emails to this address.']);
    }

    private function find(string $token): NewsletterSubscriber
    {
        return NewsletterSubscriber::query()->where('manage_token_hash', hash('sha256', $token))->firstOrFail();
    }
}
