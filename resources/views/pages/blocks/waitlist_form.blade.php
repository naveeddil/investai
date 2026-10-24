<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-[.8fr_1.2fr] lg:px-8 lg:py-28" id="waitlist">
    <div class="scroll-reveal grid content-start gap-5"><p class="eyebrow">{{ $data['eyebrow'] ?? 'Early access' }}</p><h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Join the waitlist' }}</h2><p class="site-muted text-lg leading-8">{{ $data['text'] ?? '' }}</p></div>
    <form class="site-surface site-line grid gap-5 rounded-[2rem] border p-6 sm:p-9" method="POST" action="{{ route('waitlist.store') }}">@csrf
        <div class="grid gap-5 sm:grid-cols-2"><label class="grid gap-2 text-sm">Name<input class="site-outline rounded-xl border bg-transparent px-4 py-3" name="name" value="{{ old('name') }}" required></label><label class="grid gap-2 text-sm">Email<input class="site-outline rounded-xl border bg-transparent px-4 py-3" type="email" name="email" value="{{ old('email') }}" required></label></div>
        <label class="grid gap-2 text-sm">I am joining as<select class="site-outline site-surface rounded-xl border px-4 py-3" name="audience_type" required>@foreach (['individual' => 'An individual investor', 'institution' => 'An organisation', 'advisor' => 'A financial adviser', 'partner' => 'A potential partner', 'other' => 'Other'] as $value => $label)<option value="{{ $value }}" @selected(old('audience_type') === $value)>{{ $label }}</option>@endforeach</select></label>
        <label class="site-muted flex items-start gap-3 text-sm"><input class="mt-1" type="checkbox" name="consent" value="1" required><span>I agree that InvestAI may email me about platform development and early access. I can unsubscribe at any time.</span></label>
        <button class="site-button rounded-full px-6 py-3.5 font-semibold" type="submit">Join the waitlist</button>
        @if (session('waitlist_success'))<p class="text-sm text-emerald-400" role="status">{{ session('waitlist_success') }}</p>@endif
        @if ($errors->any())<p class="text-sm text-rose-400">Please review the form and try again.</p>@endif
    </form>
</div>
