<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28">
    <div class="scroll-reveal grid max-w-3xl gap-5">
        @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2>
        @if (! empty($data['intro']))<p class="site-muted text-lg leading-8">{{ $data['intro'] }}</p>@endif
    </div>
    <div class="scroll-reveal card overflow-hidden">
        <div class="site-line hidden border-b bg-[var(--canvas-soft)] px-7 py-4 text-sm font-semibold md:grid md:grid-cols-[.8fr_1.1fr_1.1fr] md:gap-8">
            <span class="site-muted">{{ $data['topic_label'] ?? 'Area' }}</span>
            <span class="site-muted">{{ $data['left_label'] ?? 'Typical approach' }}</span>
            <span class="site-accent">{{ $data['right_label'] ?? 'The InvestAI approach' }}</span>
        </div>
        @foreach ($data['rows'] ?? [] as $row)
            <div class="site-line grid gap-3 border-b px-7 py-6 last:border-b-0 md:grid-cols-[.8fr_1.1fr_1.1fr] md:gap-8">
                <p class="font-semibold">{{ $row['topic'] ?? '' }}</p>
                <p class="site-muted leading-7"><span class="mb-1 block text-xs font-semibold uppercase tracking-widest md:hidden">{{ $data['left_label'] ?? 'Typical approach' }}</span>{{ $row['left'] ?? '' }}</p>
                <p class="leading-7"><span class="site-accent mb-1 block text-xs font-semibold uppercase tracking-widest md:hidden">{{ $data['right_label'] ?? 'The InvestAI approach' }}</span>{{ $row['right'] ?? '' }}</p>
            </div>
        @endforeach
    </div>
</div>
