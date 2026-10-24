<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28" id="growth-calculator">
    <div class="scroll-reveal grid max-w-3xl gap-5">
        <p class="eyebrow">{{ $data['eyebrow'] ?? 'Tool 2' }}</p>
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Investment growth & fee calculator' }}</h2>
        <p class="site-muted text-lg leading-8">{{ $data['intro'] ?? 'Explore how contributions, returns and annual charges can affect a long-term investment.' }}</p>
    </div>
    <div class="grid gap-8 lg:grid-cols-2 lg:items-start" data-investment-calculator>
        <form class="card grid gap-6 p-7" novalidate>
            <label class="grid gap-2 text-sm font-semibold">Starting amount (£)<input class="site-outline rounded-xl border bg-transparent px-4 py-3 text-base font-normal" type="number" min="0" max="10000000" step="100" value="10000" data-investment-input="initial"></label>
            <label class="grid gap-2 text-sm font-semibold">Monthly contribution (£)<input class="site-outline rounded-xl border bg-transparent px-4 py-3 text-base font-normal" type="number" min="0" max="100000" step="10" value="250" data-investment-input="monthly"></label>
            <label class="grid gap-3 text-sm font-semibold"><span class="flex justify-between gap-3">Time horizon <output class="site-accent" data-investment-output="years">10 years</output></span><input type="range" min="1" max="40" step="1" value="10" data-investment-input="years"></label>
            <label class="grid gap-3 text-sm font-semibold"><span class="flex justify-between gap-3">Illustrative annual return <output class="site-accent" data-investment-output="return">5.0%</output></span><input type="range" min="0" max="12" step="0.5" value="5" data-investment-input="return"></label>
            <label class="grid gap-3 text-sm font-semibold"><span class="flex justify-between gap-3">Annual platform and product fees <output class="site-accent" data-investment-output="fee">0.75%</output></span><input type="range" min="0" max="3" step="0.05" value="0.75" data-investment-input="fee"></label>
        </form>
        <div class="card grid gap-6 p-7" aria-live="polite">
            <div><p class="eyebrow">Illustrative value after <span data-investment-output="years-label">10 years</span></p><p class="mt-2 text-4xl font-semibold tracking-tight" data-investment-output="net-value">—</p></div>
            <dl class="grid gap-5 sm:grid-cols-2">
                <div><dt class="site-muted text-sm">Total paid in</dt><dd class="text-2xl font-semibold" data-investment-output="contributed">—</dd></div>
                <div><dt class="site-muted text-sm">Growth after fees</dt><dd class="text-2xl font-semibold" data-investment-output="growth">—</dd></div>
                <div><dt class="site-muted text-sm">Value before fees</dt><dd class="text-2xl font-semibold" data-investment-output="gross-value">—</dd></div>
                <div><dt class="site-muted text-sm">Illustrative fee impact</dt><dd class="text-2xl font-semibold" data-investment-output="fee-impact">—</dd></div>
            </dl>
            <p class="site-muted text-xs leading-5">Illustration only. Returns are not guaranteed, values can fall, inflation and tax are not included, and actual charges vary by provider and product.</p>
        </div>
    </div>
</div>
