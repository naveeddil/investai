@php
    $groupColumns = match (min(max(count($data['groups'] ?? []), 1), 4)) {
        1 => 'lg:grid-cols-1',
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        default => 'lg:grid-cols-4',
    };
@endphp
<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-24">
    <div class="scroll-reveal grid max-w-3xl gap-5">
        <p class="eyebrow">{{ $data['eyebrow'] ?? 'Toolset' }}</p>
        <h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2>
        @if (! empty($data['intro']))<p class="site-muted text-lg leading-8">{{ $data['intro'] }}</p>@endif
    </div>
    <div class="grid gap-5 md:grid-cols-2 {{ $groupColumns }}">
        @foreach ($data['groups'] ?? [] as $group)
            <div class="scroll-reveal card grid content-start gap-4 p-6">
                <p class="font-semibold">{{ $group['label'] ?? '' }}</p>
                <ul class="flex flex-wrap gap-2">
                    @foreach (array_filter(array_map('trim', explode(',', (string) ($group['items'] ?? '')))) as $tool)<li class="pill">{{ $tool }}</li>@endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
