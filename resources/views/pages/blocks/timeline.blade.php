@php
    $items = $data['items'] ?? [];
    $statusLabels = ['complete' => 'Complete', 'active' => 'In progress', 'planned' => 'Planned'];
@endphp
<div class="mx-auto grid max-w-7xl gap-14 px-5 py-20 lg:px-8 lg:py-28">
    <div class="scroll-reveal grid max-w-3xl gap-5">
        <p class="eyebrow">{{ $data['eyebrow'] ?? 'Roadmap' }}</p>
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2>
        @if (! empty($data['intro']))<p class="site-muted text-lg leading-8">{{ $data['intro'] }}</p>@endif
    </div>
    <ol class="grid gap-5 {{ $columnClass(count($items)) }}">
        @foreach ($items as $item)
            @php $status = $item['status'] ?? 'planned'; @endphp
            <li class="scroll-reveal card relative grid content-start gap-5 p-7 {{ $status === 'active' ? 'ring-1 ring-[var(--accent)]' : '' }}">
                <div class="flex items-center justify-between gap-3">
                    <span class="icon-chip"><x-glyph :name="$item['icon'] ?? 'flag'" /></span>
                    @if (isset($statusLabels[$status]))<span class="pill {{ $status === 'active' ? '!border-[var(--accent)] !text-[var(--accent)]' : '' }}">{{ $statusLabels[$status] }}</span>@endif
                </div>
                <div class="grid gap-2">
                    @if (! empty($item['phase']))<p class="eyebrow !tracking-[.2em]">{{ $item['phase'] }}</p>@endif
                    <h3 class="text-xl font-semibold tracking-tight">{{ $item['heading'] ?? '' }}</h3>
                    <p class="site-muted leading-7">{{ $item['text'] ?? '' }}</p>
                </div>
            </li>
        @endforeach
    </ol>
    @if (! empty($data['note']))<p class="site-muted max-w-3xl text-sm leading-6">{{ $data['note'] }}</p>@endif
</div>
