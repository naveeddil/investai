@php
    $data = $block['data'] ?? $block;
    $type = $block['type'] ?? 'rich_text';
    $tone = match ($data['background'] ?? 'dark') {
        'light' => 'site-soft',
        'surface' => 'site-surface',
        default => '',
    };
    $partialTypes = ['service_grid', 'metrics', 'comparison', 'timeline', 'faq', 'latest_insights', 'readiness_assessment', 'savings_estimator', 'risk_calculator', 'tool_stack', 'callout', 'waitlist_form'];
    $columnClass = fn (int $count): string => match (true) {
        $count === 2 => 'md:grid-cols-2',
        $count === 4 => 'md:grid-cols-2 lg:grid-cols-4',
        default => 'md:grid-cols-3',
    };
    $isEmptyLatestInsights = $type === 'latest_insights' && collect($latestInsights ?? [])->isEmpty();
@endphp

@unless ($isEmptyLatestInsights)
<section class="{{ $tone }} site-line border-b">
    @if ($type === 'hero')
        @php $isCompact = ($data['size'] ?? 'full') === 'compact'; @endphp
        <div class="mesh">
            <div class="mx-auto grid {{ $isCompact ? 'py-16 lg:py-24' : 'min-h-[74vh] py-20 lg:py-28' }} max-w-7xl items-center gap-12 px-5 {{ (! empty($data['image']) || ($data['visual'] ?? null) === 'dashboard') ? 'lg:grid-cols-[1.05fr_.95fr]' : '' }} lg:px-8">
                <div class="reveal grid content-center gap-7">
                    <p class="eyebrow">{{ $data['eyebrow'] ?? 'InvestAI' }}</p>
                    <h1 class="{{ $isCompact ? 'section-title' : 'display-title' }} max-w-5xl font-semibold">{{ $data['heading'] ?? $page->title }}</h1>
                    @if (! empty($data['text']))<p class="site-muted max-w-2xl text-lg leading-8 sm:text-xl">{{ $data['text'] }}</p>@endif
                    @if (! empty($data['button_label']) && ! empty($data['button_url']))
                        <div class="flex flex-wrap items-center gap-3">
                            <a class="site-button inline-flex rounded-full px-6 py-3.5 font-semibold transition" href="{{ $data['button_url'] }}">{{ $data['button_label'] }} <span aria-hidden="true" class="ml-2">↗</span></a>
                            @if (! empty($data['secondary_label']) && ! empty($data['secondary_url']))
                                <a class="btn-secondary inline-flex rounded-full px-6 py-3.5 font-semibold" href="{{ $data['secondary_url'] }}">{{ $data['secondary_label'] }}</a>
                            @endif
                        </div>
                    @endif
                    @if (! empty($data['highlights']))
                        <ul class="flex flex-wrap gap-2.5" aria-label="Key strengths">
                            @foreach ($data['highlights'] as $highlight)<li class="pill"><x-glyph name="check" class="size-4 text-[var(--accent)]" />{{ is_array($highlight) ? ($highlight['text'] ?? '') : $highlight }}</li>@endforeach
                        </ul>
                    @endif
                </div>
                @if (! empty($data['image']))
                    <figure class="image-grid site-line relative min-h-96 overflow-hidden rounded-[2rem] border lg:min-h-[34rem]">
                        <img class="image-wash absolute inset-0 size-full object-cover" src="{{ $data['image'] }}" alt="{{ $data['image_alt'] ?? '' }}" fetchpriority="high">
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--canvas)]/70 via-transparent to-transparent"></div>
                    </figure>
                @elseif (($data['visual'] ?? null) === 'dashboard')
                    <x-dashboard-visual class="reveal" />
                @endif
            </div>
        </div>
    @elseif ($type === 'feature_cards')
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28">
            <div class="scroll-reveal grid gap-4 md:grid-cols-2 md:items-end">
                <h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2>
                @if (! empty($data['intro']))<p class="site-muted max-w-xl text-lg leading-8 md:justify-self-end">{{ $data['intro'] }}</p>@endif
            </div>
            <div class="grid gap-4 {{ $columnClass(count($data['items'] ?? [])) }}">
                @foreach ($data['items'] ?? [] as $index => $item)
                    <article class="scroll-reveal site-surface site-line group grid min-h-64 content-between overflow-hidden rounded-3xl border transition duration-300 hover:-translate-y-1">
                        @if (! empty($item['image']))
                            <figure class="relative aspect-[16/10] overflow-hidden"><img class="image-wash size-full object-cover transition duration-500 group-hover:scale-105" src="{{ $item['image'] }}" alt="{{ $item['image_alt'] ?? '' }}" loading="lazy"><div class="absolute inset-0 bg-gradient-to-t from-[var(--surface)]/35 to-transparent"></div></figure>
                        @endif
                        <div class="grid gap-8 p-7">
                            @if (! empty($item['icon']))<span class="icon-chip"><x-glyph :name="$item['icon']" /></span>@else<span class="site-accent text-sm font-semibold">0{{ $index + 1 }}</span>@endif
                            <div class="grid gap-3"><h3 class="text-xl font-semibold">{{ $item['heading'] ?? '' }}</h3><p class="site-muted leading-7">{{ $item['text'] ?? '' }}</p></div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @elseif ($type === 'split_content')
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-28">
            <figure class="scroll-reveal site-line min-h-96 overflow-hidden rounded-[2rem] border {{ ($data['image_side'] ?? 'left') === 'right' ? 'lg:order-2' : '' }}">
                <img class="image-wash size-full min-h-96 object-cover" src="{{ $data['image'] ?? '/images/editorial/glass-architecture.jpg' }}" alt="{{ $data['image_alt'] ?? '' }}" loading="lazy">
            </figure>
            <div class="scroll-reveal grid gap-7">
                @if (! empty($data['eyebrow']))<p class="eyebrow">{{ $data['eyebrow'] }}</p>@endif
                <h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2>
                <p class="site-muted text-lg leading-8">{{ $data['text'] ?? '' }}</p>
                @if (! empty($data['points']))
                    <ul class="grid gap-0">
                        @foreach ($data['points'] as $point)<li class="site-line flex gap-4 border-t py-4"><x-glyph name="check" class="tick size-5" /><span>{{ $point['text'] ?? '' }}</span></li>@endforeach
                    </ul>
                @endif
                @if (! empty($data['link_label']) && ! empty($data['link_url']))
                    <div><a class="site-accent inline-flex items-center gap-2 font-semibold" href="{{ $data['link_url'] }}">{{ $data['link_label'] }} <x-glyph name="arrow-right" class="size-5" /></a></div>
                @endif
            </div>
        </div>
    @elseif ($type === 'process')
        <div class="mx-auto grid max-w-7xl gap-14 px-5 py-20 lg:px-8 lg:py-28">
            <div class="scroll-reveal grid max-w-3xl gap-5"><p class="eyebrow">{{ $data['eyebrow'] ?? 'Our method' }}</p><h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2>@if (! empty($data['intro']))<p class="site-muted text-lg leading-8">{{ $data['intro'] }}</p>@endif</div>
            <ol class="grid gap-0 {{ $columnClass(count($data['steps'] ?? [])) }}">
                @foreach ($data['steps'] ?? [] as $index => $step)
                    <li class="scroll-reveal site-line grid gap-8 border-l py-3 pl-6 pr-8 md:min-h-72">
                        <span class="site-accent text-sm">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="grid content-end gap-3"><h3 class="text-xl font-semibold">{{ $step['heading'] ?? '' }}</h3><p class="site-muted leading-7">{{ $step['text'] ?? '' }}</p></div>
                    </li>
                @endforeach
            </ol>
        </div>
    @elseif ($type === 'cta')
        <div class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-24">
            <div class="scroll-reveal site-surface site-line relative overflow-hidden rounded-[2rem] border p-8 sm:p-14 lg:p-20">
                <div class="absolute -right-16 -top-28 size-80 rounded-full bg-[var(--accent)] opacity-15 blur-3xl"></div>
                <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-end">
                    <div class="grid max-w-3xl gap-5"><h2 class="section-title font-semibold">{{ $data['heading'] ?? '' }}</h2><p class="site-muted text-lg leading-8">{{ $data['text'] ?? '' }}</p></div>
                    <div class="flex flex-wrap gap-3">
                        <a class="site-button inline-flex justify-center rounded-full px-6 py-3.5 font-semibold" href="{{ $data['button_url'] ?? '/contact' }}">{{ $data['button_label'] ?? 'Contact us' }}</a>
                        @if (! empty($data['secondary_label']) && ! empty($data['secondary_url']))
                            <a class="btn-secondary inline-flex justify-center rounded-full px-6 py-3.5 font-semibold" href="{{ $data['secondary_url'] }}">{{ $data['secondary_label'] }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif ($type === 'team')
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28">
            <div class="grid max-w-3xl gap-5"><p class="eyebrow">{{ $data['eyebrow'] ?? 'Team' }}</p><h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Meet the team' }}</h2><p class="site-muted text-lg leading-8">{{ $data['text'] ?? '' }}</p></div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($teamMembers ?? [] as $member)
                    <article class="site-surface site-line overflow-hidden rounded-3xl border">
                        @if ($member->photo)<img class="aspect-[4/3] w-full object-cover" src="{{ $member->photo }}" alt="{{ $member->photo_alt ?: $member->name }}" loading="lazy">@endif
                        <div class="grid gap-3 p-7"><h3 class="text-xl font-semibold">{{ $member->name }}</h3><p class="site-accent text-sm">{{ $member->role }}</p><p class="site-muted leading-7">{{ $member->bio }}</p>@if ($member->linkedin_url)<a class="font-semibold" href="{{ $member->linkedin_url }}" rel="noopener noreferrer">LinkedIn ↗</a>@endif</div>
                    </article>
                @empty
                    <div class="site-surface site-line rounded-3xl border p-8 sm:col-span-2"><h3 class="text-xl font-semibold">Profiles are being updated.</h3><p class="site-muted mt-3">Verified team names, roles and photographs will appear here after approval.</p></div>
                @endforelse
            </div>
        </div>
    @elseif ($type === 'consultation_form')
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-[.8fr_1.2fr] lg:px-8 lg:py-28">
            <div class="grid content-start gap-5"><p class="eyebrow">Consultation request</p><h1 class="section-title font-semibold">{{ $data['heading'] ?? 'Request a callback' }}</h1><p class="site-muted text-lg leading-8">{{ $data['text'] ?? '' }}</p></div>
            <form class="site-surface site-line grid gap-5 rounded-[2rem] border p-6 sm:p-9" method="POST" action="{{ route('consultation.store') }}">@csrf
                <div class="grid gap-5 sm:grid-cols-2"><label class="grid gap-2 text-sm">Name<input class="site-outline bg-transparent rounded-xl border px-4 py-3" name="name" value="{{ old('name') }}" required></label><label class="grid gap-2 text-sm">Email<input class="site-outline bg-transparent rounded-xl border px-4 py-3" type="email" name="email" value="{{ old('email') }}" required></label></div>
                <div class="grid gap-5 sm:grid-cols-2"><label class="grid gap-2 text-sm">Phone<input class="site-outline bg-transparent rounded-xl border px-4 py-3" name="phone" value="{{ old('phone') }}"></label><label class="grid gap-2 text-sm">Company<input class="site-outline bg-transparent rounded-xl border px-4 py-3" name="company" value="{{ old('company') }}"></label></div>
                <div class="grid gap-5 sm:grid-cols-2"><label class="grid gap-2 text-sm">Preferred contact<select class="site-outline site-surface rounded-xl border px-4 py-3" name="preferred_contact"><option value="email">Email</option><option value="phone">Phone</option></select></label><label class="grid gap-2 text-sm">Preferred time<input class="site-outline bg-transparent rounded-xl border px-4 py-3" name="preferred_time" value="{{ old('preferred_time') }}" placeholder="e.g. weekday afternoons"></label></div>
                @php $selectedTopic = old('topic', request('topic', 'consulting')); @endphp
                <label class="grid gap-2 text-sm">Topic<select class="site-outline site-surface rounded-xl border px-4 py-3" name="topic">@foreach (['consulting' => 'Business & analytics consulting', 'platform' => 'InvestAI platform', 'partnership' => 'Partnership', 'research' => 'Research approach', 'media' => 'Media', 'general' => 'General'] as $value => $label)<option value="{{ $value }}" @selected($selectedTopic === $value)>{{ $label }}</option>@endforeach</select></label>
                <label class="grid gap-2 text-sm">What would you like to discuss?<textarea class="site-outline min-h-32 bg-transparent rounded-xl border px-4 py-3" name="message">{{ old('message') }}</textarea></label>
                <label class="site-muted flex items-start gap-3 text-sm"><input class="mt-1" type="checkbox" name="consent" value="1" required><span>I agree that InvestAI may use these details to respond to this request.</span></label>
                <button class="site-button rounded-full px-6 py-3.5 font-semibold" type="submit">Request consultation</button>
                @if (session('consultation_success'))<p class="text-sm text-emerald-400">{{ session('consultation_success') }}</p>@endif
                @if ($errors->any())<p class="text-sm text-rose-400">Please review the form and try again.</p>@endif
            </form>
        </div>
    @elseif ($type === 'contact_form')
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:grid-cols-[.8fr_1.2fr] lg:px-8 lg:py-28">
            <div class="grid content-start gap-5"><p class="eyebrow">Contact</p><h1 class="section-title font-semibold">{{ $data['heading'] ?? 'Start a conversation' }}</h1><p class="site-muted text-lg leading-8">{{ $data['text'] ?? 'Tell us how we can help.' }}</p></div>
            <form class="site-surface site-line grid gap-5 rounded-[2rem] border p-6 sm:p-9" method="POST" action="{{ route('contact.store') }}">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2"><label class="grid gap-2 text-sm">Name<input class="site-outline bg-transparent rounded-xl border px-4 py-3" name="name" value="{{ old('name') }}" required></label><label class="grid gap-2 text-sm">Email<input class="site-outline bg-transparent rounded-xl border px-4 py-3" type="email" name="email" value="{{ old('email') }}" required></label></div>
                <label class="grid gap-2 text-sm">Company <span class="site-muted">(optional)</span><input class="site-outline bg-transparent rounded-xl border px-4 py-3" name="company" value="{{ old('company') }}"></label>
                <label class="grid gap-2 text-sm">How can we help?<select class="site-outline site-surface rounded-xl border px-4 py-3" name="enquiry_type" required>@foreach (['consulting' => 'Business & analytics consulting', 'general' => 'General enquiry', 'partnership' => 'Partnership', 'investment' => 'Investment', 'media' => 'Media', 'other' => 'Other'] as $value => $label)<option value="{{ $value }}" @selected(old('enquiry_type') === $value)>{{ $label }}</option>@endforeach</select></label>
                <label class="grid gap-2 text-sm">Message<textarea class="site-outline min-h-36 bg-transparent rounded-xl border px-4 py-3" name="message" required>{{ old('message') }}</textarea></label>
                <button class="site-button rounded-full px-6 py-3.5 font-semibold" type="submit">Send enquiry</button>
                @if (session('contact_success'))<p class="text-sm text-emerald-400">{{ session('contact_success') }}</p>@endif
                @if ($errors->any())<p class="text-sm text-rose-400">Please review the form and try again.</p>@endif
            </form>
        </div>
    @elseif (in_array($type, $partialTypes, true))
        @include('pages.blocks.'.$type, ['data' => $data, 'columnClass' => $columnClass])
    @else
        <div class="mx-auto grid max-w-7xl px-5 py-20 lg:px-8 lg:py-28">
            <div class="grid max-w-3xl gap-6">
                @if (! empty($data['heading']))<h2 class="section-title font-semibold">{{ $data['heading'] }}</h2>@endif
                @if (! empty($data['text']))<p class="site-muted whitespace-pre-line text-lg leading-8">{{ $data['text'] }}</p>@endif
            </div>
        </div>
    @endif
</section>
@endunless
