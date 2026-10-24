@php
    $dimensions = [
        'strategy' => ['label' => 'Strategy & decisions', 'service' => 'Business Strategy & Performance', 'url' => route('pages.show', 'business-strategy'), 'advice' => 'Agree the handful of decisions and KPIs that matter most, then make sure every initiative is judged against them with explicit assumptions.'],
        'data' => ['label' => 'Data foundations', 'service' => 'Data & Analytics', 'url' => route('pages.show', 'data-analytics'), 'advice' => 'Establish shared definitions and a single trusted source for core metrics before adding more dashboards or models.'],
        'governance' => ['label' => 'People & governance', 'service' => 'AI Strategy & Automation', 'url' => route('pages.show', 'ai-advisory'), 'advice' => 'Name owners for data and analytics, and set clear rules for access, privacy and how AI tools may be used with your information.'],
        'automation' => ['label' => 'AI & automation', 'service' => 'AI Strategy & Automation', 'url' => route('pages.show', 'ai-advisory'), 'advice' => 'Shortlist a few repeatable tasks, run supervised pilots with human review, and measure the time or quality gained before scaling.'],
    ];

    $questions = [
        ['dimension' => 'strategy', 'text' => 'How clearly are your priority decisions and KPIs defined?', 'options' => ['Not defined — teams use different measures', 'Defined informally in some areas', 'Defined for most areas and reviewed regularly', 'Defined, owned and reviewed against outcomes']],
        ['dimension' => 'strategy', 'text' => 'How are major initiatives or investments evaluated?', 'options' => ['Mostly instinct or precedent', 'Basic business cases that are rarely revisited', 'Consistent business cases with agreed assumptions', 'Consistent cases with scenarios, review triggers and follow-up']],
        ['dimension' => 'data', 'text' => 'How trustworthy and accessible is the data behind your reporting?', 'options' => ['Scattered across spreadsheets and systems', 'Partly centralised; manual fixes are common', 'Mostly centralised with documented definitions', 'Governed, quality-checked and available from one trusted source']],
        ['dimension' => 'data', 'text' => 'How is management information produced?', 'options' => ['Manually, on request', 'Mostly manual with some templates', 'Semi-automated on a regular cycle', 'Automated, self-serve and refreshed on a schedule']],
        ['dimension' => 'governance', 'text' => 'Who owns data and analytics in your organisation?', 'options' => ['No clear owner', 'A few individuals, informally', 'A defined team or role', 'Clear ownership with agreed standards']],
        ['dimension' => 'governance', 'text' => 'How are privacy, security and AI-tool risks managed?', 'options' => ['No formal approach', 'General policies, not specific to data or AI', 'Specific policies and access controls', 'Policies, access controls, audit trails and regular review']],
        ['dimension' => 'automation', 'text' => 'How is AI used in your organisation today?', 'options' => ['Not used, or used without guidance', 'Small experiments by individuals', 'A few supervised use cases with guidance', 'Prioritised use cases with owners, measures and human review']],
        ['dimension' => 'automation', 'text' => 'How much routine work is automated?', 'options' => ['Almost none', 'A few simple automations', 'Several processes automated', 'Automation is planned, monitored and improved']],
    ];

    $levels = [
        ['max' => 14, 'name' => 'Foundational', 'text' => 'The biggest gains are likely to come from agreeing definitions, cleaning up core data and building a single view of the numbers that matter.'],
        ['max' => 20, 'name' => 'Developing', 'text' => 'You have useful building blocks. Consolidating them, clarifying ownership and automating recurring reporting would raise reliability quickly.'],
        ['max' => 26, 'name' => 'Established', 'text' => 'Your foundations are solid. The opportunity is to scale what works, tighten governance and introduce well-supervised AI where it adds value.'],
        ['max' => 32, 'name' => 'Advanced', 'text' => 'You are well placed. Focus on continuous improvement, measuring value from AI and analytics, and keeping controls proportionate as use grows.'],
    ];

    $config = ['dimensions' => $dimensions, 'levels' => $levels, 'contactUrl' => route('pages.show', 'consultation').'?topic=consulting'];
@endphp
<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28" id="readiness">
    <div class="scroll-reveal grid max-w-3xl gap-5">
        <p class="eyebrow">{{ $data['eyebrow'] ?? 'Self-assessment' }}</p>
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Analytics and AI readiness check' }}</h2>
        <p class="site-muted text-lg leading-8">{{ $data['intro'] ?? 'Answer eight short questions to see where your organisation stands across strategy, data, governance and automation.' }}</p>
        <p class="site-muted flex items-start gap-3 text-sm leading-6"><x-glyph name="lock" class="tick size-5" />This tool runs entirely in your browser. Your answers are not sent to us or stored, and the result is indicative, not a formal assessment.</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.25fr_.75fr] lg:items-start" data-readiness data-config="{{ json_encode($config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}">
        <form class="grid gap-5" data-readiness-form novalidate>
            @foreach ($questions as $index => $question)
                <fieldset class="card grid gap-4 p-6" data-dimension="{{ $question['dimension'] }}">
                    <legend class="px-0 text-lg font-semibold tracking-tight"><span class="site-accent mr-2 text-sm">{{ $index + 1 }}/{{ count($questions) }}</span>{{ $question['text'] }}</legend>
                    <div class="grid gap-2.5 sm:grid-cols-2">
                        @foreach ($question['options'] as $optionIndex => $option)
                            <label class="option-card block cursor-pointer">
                                <input class="sr-only" type="radio" name="q{{ $index }}" value="{{ $optionIndex + 1 }}">
                                <span class="site-line site-muted block h-full rounded-xl border px-4 py-3 text-sm leading-6 transition hover:border-[var(--accent)]">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
            <div class="flex flex-wrap items-center gap-4">
                <button class="site-button rounded-full px-6 py-3.5 font-semibold" type="submit">Show my results</button>
                <p class="site-muted text-sm" data-progress aria-live="polite">0 of {{ count($questions) }} answered</p>
            </div>
        </form>

        <aside class="card sticky top-24 grid gap-6 p-7" data-results aria-live="polite" tabindex="-1">
            <div class="grid gap-3" data-results-placeholder>
                <span class="icon-chip"><x-glyph name="clipboard" /></span>
                <h3 class="text-xl font-semibold tracking-tight">Your results appear here</h3>
                <p class="site-muted leading-7">Complete the questions to see an overall maturity level, a breakdown by area and suggested next steps.</p>
            </div>
            <div class="hidden gap-6" data-results-body>
                <div class="grid gap-2">
                    <p class="eyebrow">Overall level</p>
                    <p class="text-3xl font-semibold tracking-tight" data-level-name></p>
                    <p class="site-muted leading-7" data-level-text></p>
                </div>
                <ul class="grid gap-4" data-dimension-bars></ul>
                <div class="grid gap-3">
                    <p class="eyebrow">Suggested next steps</p>
                    <ul class="grid gap-4" data-next-steps></ul>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a class="site-button inline-flex rounded-full px-5 py-3 font-semibold" data-contact-link href="#">Discuss my results</a>
                    <button class="btn-secondary rounded-full px-5 py-3 font-semibold" type="button" data-reset>Start again</button>
                </div>
            </div>
        </aside>
    </div>
</div>
