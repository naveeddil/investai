@php
    $items = collect($data['items'] ?? [])->filter(fn ($item) => ! empty($item['question']) && ! empty($item['answer']))->values();
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $items->map(fn ($item): array => [
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
        ])->all(),
    ];
@endphp
<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-[.8fr_1.2fr] lg:px-8 lg:py-28">
    <div class="scroll-reveal grid content-start gap-5">
        <p class="eyebrow">{{ $data['eyebrow'] ?? 'FAQ' }}</p>
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Common questions' }}</h2>
        @if (! empty($data['intro']))<p class="site-muted text-lg leading-8">{{ $data['intro'] }}</p>@endif
    </div>
    <div class="grid gap-3">
        @foreach ($items as $item)
            <details class="faq-item card scroll-reveal px-6 py-5" @if ($loop->first && ($data['open_first'] ?? false)) open @endif>
                <summary class="flex items-start justify-between gap-6 text-lg font-semibold tracking-tight"><span>{{ $item['question'] }}</span><svg class="faq-chevron site-accent mt-1 size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></summary>
                <p class="site-muted mt-4 whitespace-pre-line leading-7">{{ $item['answer'] }}</p>
            </details>
        @endforeach
    </div>
</div>
<script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
