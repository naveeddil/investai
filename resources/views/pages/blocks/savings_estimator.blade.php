<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28" id="estimator">
    <div class="scroll-reveal grid max-w-3xl gap-5">
        <p class="eyebrow">{{ $data['eyebrow'] ?? 'Estimator' }}</p>
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Reporting time estimator' }}</h2>
        <p class="site-muted text-lg leading-8">{{ $data['intro'] ?? 'Estimate how much time and cost recurring manual reporting absorbs — and what a realistic share of automation could free up.' }}</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-2 lg:items-start" data-estimator>
        <form class="card grid gap-6 p-7" novalidate>
            <label class="grid gap-2 text-sm font-semibold">People involved in recurring reporting
                <input class="site-outline rounded-xl border bg-transparent px-4 py-3 text-base font-normal" type="number" inputmode="numeric" min="1" max="1000" step="1" value="5" data-input="people">
            </label>
            <label class="grid gap-2 text-sm font-semibold">Hours per person per week on data preparation and reporting
                <input class="site-outline rounded-xl border bg-transparent px-4 py-3 text-base font-normal" type="number" inputmode="decimal" min="0" max="60" step="0.5" value="6" data-input="hours">
            </label>
            <label class="grid gap-2 text-sm font-semibold">Average fully loaded cost per hour (£)
                <input class="site-outline rounded-xl border bg-transparent px-4 py-3 text-base font-normal" type="number" inputmode="decimal" min="0" max="1000" step="1" value="35" data-input="rate">
            </label>
            <label class="grid gap-3 text-sm font-semibold">
                <span class="flex items-center justify-between gap-4">Share of the work that could realistically be automated <output class="site-accent text-base" data-output="share">40%</output></span>
                <input type="range" min="10" max="80" step="5" value="40" data-input="share" aria-label="Share of work that could be automated">
            </label>
            <p class="site-muted text-xs leading-5">Assumes 46 working weeks a year and a 37.5-hour working week.</p>
        </form>

        <div class="card grid gap-6 p-7" aria-live="polite">
            <div class="grid gap-1"><p class="eyebrow">Time spent today</p><p class="text-4xl font-semibold tracking-tight"><span data-output="hours-total">—</span> <span class="site-muted text-lg font-normal">hours a year</span></p><p class="site-muted" data-output="cost-total">—</p></div>
            <div class="site-line grid gap-1 border-t pt-6"><p class="eyebrow">Potentially freed up</p><p class="text-4xl font-semibold tracking-tight"><span class="site-accent" data-output="hours-saved">—</span> <span class="site-muted text-lg font-normal">hours a year</span></p><p class="site-muted"><span data-output="cost-saved">—</span> of capacity · about <span data-output="fte-saved">—</span> full-time equivalents</p></div>
            <p class="site-muted text-xs leading-5">Illustrative arithmetic based only on the figures you enter. It is not a forecast, a quotation or a guarantee of savings; actual results depend on process, data quality and implementation.</p>
            <div><a class="site-button inline-flex rounded-full px-5 py-3 font-semibold" href="{{ route('pages.show', 'consultation') }}?topic=consulting">Test these numbers with us</a></div>
        </div>
    </div>
</div>
