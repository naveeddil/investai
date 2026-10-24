<div class="mx-auto max-w-7xl px-5 py-12 lg:px-8 lg:py-16">
    <aside class="scroll-reveal site-surface site-line flex gap-5 rounded-3xl border border-l-4 !border-l-[var(--accent)] p-6 sm:p-8" role="note">
        <span class="icon-chip shrink-0"><x-glyph :name="$data['icon'] ?? 'shield'" /></span>
        <div class="grid gap-2">
            <h2 class="text-xl font-semibold tracking-tight">{{ $data['heading'] ?? '' }}</h2>
            <p class="site-muted max-w-4xl leading-7">{{ $data['text'] ?? '' }}</p>
            @if (! empty($data['link_label']) && ! empty($data['link_url']))<p><a class="site-accent inline-flex items-center gap-2 font-semibold" href="{{ $data['link_url'] }}">{{ $data['link_label'] }} <x-glyph name="arrow-right" class="size-5" /></a></p>@endif
        </div>
    </aside>
</div>
