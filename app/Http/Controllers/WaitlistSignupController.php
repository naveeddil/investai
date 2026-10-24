<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWaitlistSignupRequest;
use App\Models\WaitlistSignup;
use Illuminate\Http\RedirectResponse;

class WaitlistSignupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreWaitlistSignupRequest $request): RedirectResponse
    {
        WaitlistSignup::updateOrCreate(
            ['email' => $request->string('email')->lower()->toString()],
            [
                ...$request->safe()->only(['name', 'audience_type']),
                'consent' => true,
                'consent_version' => '2026-10',
                'consented_at' => now(),
                'submitted_at' => now(),
                'status' => 'active',
                'unsubscribed_at' => null,
            ],
        );

        return back()->with('waitlist_success', 'You have joined the InvestAI waitlist.');
    }
}
