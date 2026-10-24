<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactSubmissionRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;

class ContactSubmissionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreContactSubmissionRequest $request): RedirectResponse
    {
        ContactSubmission::create([
            ...$request->safe()->only(['name', 'email', 'company', 'enquiry_type', 'message']),
            'submitted_at' => now(),
            'ip_hash' => hash_hmac('sha256', (string) $request->ip(), config('app.key')),
        ]);

        return back()->with('contact_success', 'Thank you. Your enquiry has been received.');
    }
}
