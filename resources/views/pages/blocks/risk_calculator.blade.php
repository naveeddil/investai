@php
    $assumptions = \App\Services\RiskEstimator::assumptions();
    $aiEnabled = (string) config('services.anthropic.key') !== '';
    $presets = [
        'Cautious' => ['equities' => 15, 'bonds' => 50, 'cash' => 30, 'property' => 5, 'alternatives' => 0],
        'Balanced' => ['equities' => 50, 'bonds' => 30, 'cash' => 10, 'property' => 5, 'alternatives' => 5],
        'Growth' => ['equities' => 75, 'bonds' => 10, 'cash' => 0, 'property' => 10, 'alternatives' => 5],
    ];
    $config = [
        'assumptions' => $assumptions,
        'presets' => $presets,
        'explainUrl' => route('tools.risk-explanation'),
        'aiEnabled' => $aiEnabled,
    ];
@endphp
<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28" id="risk">
    <div class="scroll-reveal grid max-w-3xl gap-5">
        <p class="eyebrow">{{ $data['eyebrow'] ?? 'Tool 3' }}</p>
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Financial risk calculator' }}</h2>
        <p class="site-muted text-lg leading-8">{{ $data['intro'] ?? 'Explore how a mix of assets could behave in a good year and a bad one. Use it without AI for transparent arithmetic, or switch on AI for a plain-English explanation of the results.' }}</p>
        <p class="site-muted flex items-start gap-3 text-sm leading-6"><x-glyph name="scale" class="tick size-5" />Educational illustration only. It uses simplified, illustrative assumptions — not forecasts — and is not personal advice or a recommendation to buy or sell anything.</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[.95fr_1.05fr] lg:items-start" data-risk data-config="{{ json_encode($config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}">
        <form class="card grid gap-6 p-7" data-risk-form novalidate>
            @csrf
            <fieldset class="grid gap-3">
                <legend class="mb-1 text-sm font-semibold">Mode</legend>
                <div class="grid grid-cols-2 gap-2" role="radiogroup">
                    <label class="option-card block cursor-pointer"><input class="sr-only" type="radio" name="mode" value="plain" checked><span class="site-line site-muted block rounded-xl border px-4 py-3 text-center text-sm font-semibold transition hover:border-[var(--accent)]">Without AI<small class="mt-0.5 block font-normal">Instant, transparent maths</small></span></label>
                    <label class="option-card block cursor-pointer"><input class="sr-only" type="radio" name="mode" value="ai"><span class="site-line site-muted block rounded-xl border px-4 py-3 text-center text-sm font-semibold transition hover:border-[var(--accent)]">With AI<small class="mt-0.5 block font-normal">Adds a plain-English explanation</small></span></label>
                </div>
            </fieldset>

            <fieldset class="grid gap-4">
                <legend class="mb-1 text-sm font-semibold">Mix of assets (% of portfolio)</legend>
                <div class="flex flex-wrap gap-2" aria-label="Example mixes">
                    @foreach ($presets as $label => $preset)<button class="pill transition hover:!border-[var(--accent)]" type="button" data-preset="{{ $label }}">{{ $label }} example</button>@endforeach
                </div>
                @foreach ($assumptions['classes'] as $key => $class)
                    <label class="grid grid-cols-[1fr_6rem] items-center gap-3 text-sm">{{ $class['label'] }}
                        <span class="relative"><input class="site-outline w-full rounded-xl border bg-transparent py-2.5 pl-4 pr-8 text-base" type="number" inputmode="decimal" min="0" max="100" step="1" value="{{ $presets['Balanced'][$key] }}" data-weight="{{ $key }}"><span class="site-muted pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">%</span></span>
                    </label>
                @endforeach
                <p class="text-sm font-semibold" data-total role="status">Total: 100%</p>
            </fieldset>

            <div class="grid gap-5 sm:grid-cols-2">
                <label class="grid gap-3 text-sm font-semibold"><span class="flex justify-between gap-3">Time horizon <output class="site-accent" data-output="years">10 years</output></span><input type="range" min="1" max="40" step="1" value="10" data-years aria-label="Time horizon in years"></label>
                <label class="grid gap-2 text-sm font-semibold">Illustrative amount (£)<input class="site-outline rounded-xl border bg-transparent px-4 py-3 text-base font-normal" type="number" inputmode="numeric" min="100" max="100000000" step="100" value="10000" data-amount></label>
            </div>
            <label class="grid gap-2 text-sm font-semibold">Largest fall in a single year you could tolerate
                <select class="site-outline site-surface rounded-xl border px-4 py-3 text-base font-normal" data-tolerance>@foreach ([5, 10, 15, 20, 30, 40, 50] as $level)<option value="{{ $level }}" @selected($level === 15)>{{ $level }}%</option>@endforeach</select>
            </label>

            <details class="site-line rounded-xl border px-4 py-3 text-sm">
                <summary class="cursor-pointer font-semibold">How it is calculated, and the assumptions used</summary>
                <div class="site-muted mt-3 grid gap-3 leading-6">
                    <p>Each asset class has an assumed average return and volatility (the size of a typical yearly swing). Volatility for the whole mix combines them using assumed correlations, so holdings that move differently reduce overall risk. A “bad year” is the average return minus 1.645 standard deviations (about a 1-in-20 outcome); a “very bad year” uses 2.326 (about 1-in-100).</p>
                    <ul class="grid gap-1.5">
                        @foreach ($assumptions['classes'] as $class)<li class="flex justify-between gap-4"><span>{{ $class['label'] }}</span><span>{{ $class['expectedReturn'] }}% return · {{ $class['volatility'] }}% volatility</span></li>@endforeach
                    </ul>
                    <p>Real markets have fatter tails than this model assumes, so actual losses can be larger. The figures are illustrations, not predictions.</p>
                </div>
            </details>
        </form>

        <div class="grid gap-6">
            <div class="card grid gap-6 p-7" aria-live="polite" data-risk-results>
                <div class="flex flex-wrap items-center justify-between gap-3"><p class="eyebrow">Risk of this mix</p><p class="pill !border-[var(--accent)] !text-[var(--accent)]" data-band>—</p></div>
                <div class="flex h-3 overflow-hidden rounded-full" data-stack role="img" aria-label="Allocation across asset classes"></div>
                <dl class="grid gap-5 sm:grid-cols-2">
                    <div><dt class="site-muted text-sm">Typical yearly swing</dt><dd class="text-3xl font-semibold tracking-tight" data-output="volatility">—</dd></div>
                    <div><dt class="site-muted text-sm">Assumed average yearly return</dt><dd class="text-3xl font-semibold tracking-tight" data-output="expected">—</dd></div>
                    <div><dt class="site-muted text-sm">A bad year (about 1 in 20)</dt><dd class="text-3xl font-semibold tracking-tight" data-output="bad">—</dd><dd class="site-muted text-sm" data-output="bad-amount"></dd></div>
                    <div><dt class="site-muted text-sm">A very bad year (about 1 in 100)</dt><dd class="text-3xl font-semibold tracking-tight" data-output="verybad">—</dd><dd class="site-muted text-sm" data-output="verybad-amount"></dd></div>
                </dl>
                <div class="site-line grid gap-2 border-t pt-5">
                    <p class="text-sm font-semibold" data-output="range-title">Possible range after 10 years</p>
                    <p class="text-lg font-semibold" data-output="range">—</p>
                    <p class="site-muted text-sm leading-6">Low and high cases are roughly the 5th and 95th percentiles; the middle is the median. Outcomes outside this range are possible.</p>
                </div>
                <div class="site-line grid gap-2 border-t pt-5">
                    <p class="text-sm font-semibold">Compared with your stated tolerance</p>
                    <p class="leading-7" data-output="verdict">—</p>
                    <ul class="site-muted grid gap-1.5 text-sm leading-6" data-flags></ul>
                </div>
            </div>

            <div class="card hidden gap-5 p-7" data-ai-panel>
                <div class="flex items-center gap-3"><span class="icon-chip"><x-glyph name="sparkles" /></span><h3 class="text-xl font-semibold tracking-tight">AI explanation</h3></div>
                @if ($aiEnabled)
                    <p class="site-muted text-sm leading-6">A language model will explain the figures above in plain English. Only the percentages, horizon and tolerance are sent to our AI provider — not your name, contact details or the amount. We do not store them. AI can make mistakes, so treat the explanation as a starting point for your own thinking.</p>
                    <label class="site-muted flex items-start gap-3 text-sm"><input class="mt-1" type="checkbox" data-ai-consent><span>I understand these figures will be processed by an external AI service to generate the explanation.</span></label>
                    <div><button class="site-button rounded-full px-5 py-3 font-semibold disabled:opacity-50" type="button" data-ai-button disabled>Explain these results</button></div>
                    <div class="grid gap-3" data-ai-output aria-live="polite"></div>
                    <p class="site-muted text-xs leading-5">AI-generated, general education only — not personal financial advice or a recommendation.</p>
                @else
                    <p class="site-muted leading-7">AI explanations are not switched on for this site yet. The calculation without AI works fully and uses exactly the same figures the AI would explain.</p>
                @endif
            </div>
        </div>
    </div>
</div>
