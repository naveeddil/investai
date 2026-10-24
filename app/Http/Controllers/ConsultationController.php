<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationRequest;
use App\Models\ConsultationRequest;
use Illuminate\Http\RedirectResponse;

class ConsultationController extends Controller
{
    public function __invoke(StoreConsultationRequest $request): RedirectResponse
    {
        ConsultationRequest::create([
            ...$request->safe()->except('consent'),
            'consent' => true,
            'submitted_at' => now(),
            'ip_hash' => hash_hmac('sha256', (string) $request->ip(), config('app.key')),
        ]);

        return back()->with('consultation_success', 'Thank you. We will contact you using your preferred method.');
    }
}
