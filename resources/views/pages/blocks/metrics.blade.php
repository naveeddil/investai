@php
    $metricColumns = match (min(max(count($data['items'] ?? []), 1), 4)) {
        1 => 'lg:grid-cols-1',
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        default => 'lg:grid-cols-4',
    };
@endphp
<div class="mx-auto max-w-7xl px-5 py-12 lg:px-8 lg:py-16">
    @if (! empty($data['heading']))<p class="eyebrow mb-8 text-center">{{ $data['heading'] }}</p>@endif
    <dl class="grid gap-8 sm:grid-cols-2 {{ $metricColumns }}">
        @foreach ($data['items'] ?? [] as $item)
            <div class="scroll-reveal site-line grid gap-2 border-l pl-6">
                <dt class="site-muted order-2 leading-6">{{ $item['label'] ?? '' }}</dt>
                <dd class="order-1 text-5xl font-semibold tracking-tight lg:text-6xl"><span class="site-accent">{{ $item['value'] ?? '' }}</span></dd>
            </div>
        @endforeach
    </dl>
</div>
