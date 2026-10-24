@php
    $items = $data['items'] ?? [];
    $gridClass = match (true) {
        count($items) === 2 => 'md:grid-cols-2',
        count($items) === 4 => 'md:grid-cols-2 xl:grid-cols-4',
        default => 'md:grid-cols-2 lg:grid-cols-3',
    };
@endphp
<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28">
    <div class="scroll-reveal grid gap-4 md:grid-cols-2 md:items-end">
        <div class="grid gap-4">
            @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
            <h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2>
        </div>
        @if (! empty($data['intro']))<p class="site-muted max-w-xl text-lg leading-8 md:justify-self-end">{{ $data['intro'] }}</p>@endif
    </div>
    <div class="grid gap-5 {{ $gridClass }}">
        @foreach ($items as $item)
            <article class="scroll-reveal card card-hover group relative grid content-between gap-8 p-7">
                <div class="grid gap-5">
                    <span class="icon-chip"><x-glyph :name="$item['icon'] ?? 'sparkles'" /></span>
                    <div class="grid gap-3">
                        <h3 class="text-xl font-semibold tracking-tight">{{ $item['heading'] ?? '' }}</h3>
                        <p class="site-muted leading-7">{{ $item['text'] ?? '' }}</p>
                    </div>
                </div>
                @if (! empty($item['link_url']))
                    <a class="site-accent inline-flex items-center gap-2 font-semibold after:absolute after:inset-0" href="{{ $item['link_url'] }}">{{ $item['link_label'] ?? 'Learn more' }} <x-glyph name="arrow-right" class="size-5 transition group-hover:translate-x-1" /></a>
                @endif
            </article>
        @endforeach
    </div>
</div>
