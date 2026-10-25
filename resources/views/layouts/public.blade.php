<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteTheme ?? $settingsTheme ?? 'ivory' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $pageTitle = $metaTitle ?? (($page->seo_title ?? null) ?: (($page->title ?? 'InvestAI').' | InvestAI'));
        $pageDescription = $metaDescription ?? (($page->seo_description ?? null) ?: ($page->description ?? $defaultSeoDescription ?? 'Make clearer investment decisions with AI-assisted research.'));
        $socialTitle = ($page->open_graph_title ?? null) ?: $pageTitle;
        $socialDescription = ($page->open_graph_description ?? null) ?: $pageDescription;
        $socialImage = url('/images/investai-logo.png');
        $organizationSchema = [
            chr(64).'context' => 'https://schema.org',
            chr(64).'type' => 'Organization',
            'name' => 'InvestAI',
            'url' => url('/'),
            'logo' => $socialImage,
            'description' => 'AI-assisted investment research focused on personal goals, transparent reasoning and risk awareness.',
        ];
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ isset($page) && ! $page->index_by_search_engines ? 'noindex' : 'index' }},{{ isset($page) && ! $page->robots_follow ? 'nofollow' : 'follow' }}">
    <meta name="theme-color" content="#050816">
    <meta property="og:site_name" content="InvestAI">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $socialTitle }}">
    <meta property="og:description" content="{{ $socialDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $socialImage }}">
    <meta property="og:image:secure_url" content="{{ $socialImage }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="973">
    <meta property="og:image:height" content="981">
    <meta property="og:image:alt" content="InvestAI — invest with intelligence">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $socialTitle }}">
    <meta name="twitter:description" content="{{ $socialDescription }}">
    <meta name="twitter:image" content="{{ $socialImage }}">
    <meta name="twitter:image:alt" content="InvestAI — invest with intelligence">
    @if (($page->canonical_url ?? null))<link rel="canonical" href="{{ $page->canonical_url }}">@endif
    <link rel="icon" href="{{ $brandFavicon ?? '/favicon.ico' }}" sizes="any">
    @if (($brandFavicon ?? '/favicon.ico') === '/favicon.ico')
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    @endif
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <script>document.documentElement.classList.add('js');</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
</head>
<body class="site-shell min-h-screen antialiased">
    @php
        $navLink = 'site-muted relative inline-flex items-center gap-1 py-2 transition hover:text-current after:absolute after:inset-x-0 after:-bottom-1 after:h-0.5 after:origin-left after:scale-x-0 after:rounded-full after:bg-[var(--accent)] after:transition-transform';
        $navActive = 'text-current after:scale-x-100';
        $mobileLink = 'rounded-xl border border-transparent px-4 py-3 transition';
        $mobileActive = 'border-[var(--line)] bg-[var(--canvas-soft)] font-semibold text-[var(--accent)]';
        $currentSlug = request()->routeIs('pages.show') ? request()->route('slug') : null;

        $href = fn (array $item): string => (isset($item['route']) ? route($item['route']) : route('pages.show', $item['slug'])).(isset($item['fragment']) ? '#'.$item['fragment'] : '');
        $isCurrent = fn (array $item): bool => isset($item['route'])
            ? request()->routeIs('insights.*')
            : $currentSlug === $item['slug'];
        $isActive = function (array $item) use ($isCurrent): bool {
            if ($isCurrent($item)) {
                return true;
            }

            foreach ($item['children'] ?? [] as $child) {
                if ($isCurrent($child)) {
                    return true;
                }
            }

            return false;
        };

        $primaryAction = config('site.primary_action');
        $primaryAction['label'] = \App\Models\SiteSetting::read('menu.cta.label', $primaryAction['label']);
        $menuKeys = ['services' => 'services', 'approach' => 'approach', 'ai-tools' => 'tools', 'about' => 'about'];
        $navigation = collect(config('site.navigation'))->filter(function ($item) use ($menuKeys) { $key = $item['route'] ?? ($menuKeys[$item['slug'] ?? ''] ?? null); $key = $key === 'insights.index' ? 'blog' : $key; return $key ? \App\Models\SiteSetting::read('menu.show.'.$key, true) : true; })->map(function ($item) use ($menuKeys) { $key = $item['route'] ?? ($menuKeys[$item['slug'] ?? ''] ?? null); $key = $key === 'insights.index' ? 'blog' : $key; if ($key) $item['label'] = \App\Models\SiteSetting::read('menu.'.$key.'.label', $item['label']); return $item; })->all();
        $primaryActive = in_array($currentSlug, ['contact', 'consultation'], true);
    @endphp
    <a href="#content" class="sr-only z-50 bg-white px-4 py-3 text-slate-950 focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to content</a>
    <header class="site-header sticky top-0 z-40 border-b backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-3.5 lg:px-8">
            <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif class="flex items-center gap-3 rounded-full text-xl font-semibold tracking-[-0.04em] {{ request()->routeIs('home') ? 'ring-2 ring-[var(--accent)] ring-offset-4 ring-offset-[var(--canvas)]' : '' }}"><img class="size-10 rounded-full bg-white object-contain p-1" src="{{ $brandLogo ?? '/images/investai-logo.png' }}" alt=""><span>{{ $brandName ?? 'InvestAI' }}</span></a>

            <nav aria-label="Primary navigation" class="hidden items-center gap-6 text-sm lg:flex">
                @foreach ($navigation as $item)
                    @if (! empty($item['children']))
                        <div class="nav-group relative">
                            <a @if($isCurrent($item)) aria-current="page" @endif aria-haspopup="true" class="{{ $navLink }} {{ $isActive($item) ? $navActive : '' }}" href="{{ $href($item) }}">{{ $item['label'] }}<svg class="size-3.5 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></a>
                            <div class="nav-panel absolute left-1/2 top-full z-50 w-[22rem] -translate-x-1/2 pt-4">
                                <div class="site-surface site-line grid gap-1 rounded-2xl border p-2.5 shadow-2xl">
                                    @foreach ($item['children'] as $child)
                                        <a @if($isCurrent($child)) aria-current="page" @endif class="group flex items-start gap-3 rounded-xl p-3 transition hover:bg-[var(--canvas-soft)] {{ $isCurrent($child) ? 'bg-[var(--canvas-soft)]' : '' }}" href="{{ $href($child) }}">
                                            <span class="icon-chip !size-10 shrink-0"><x-glyph :name="$child['icon'] ?? 'sparkles'" class="size-5" /></span>
                                            <span class="grid gap-0.5"><span class="font-semibold">{{ $child['label'] }}</span><span class="site-muted text-xs leading-5">{{ $child['text'] ?? '' }}</span></span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a @if($isCurrent($item)) aria-current="page" @endif class="{{ $navLink }} {{ $isCurrent($item) ? $navActive : '' }}" href="{{ $href($item) }}">{{ $item['label'] }}</a>
                    @endif
                @endforeach
                <a @if($primaryActive) aria-current="page" @endif class="site-button rounded-full px-5 py-2.5 font-semibold transition {{ $primaryActive ? 'ring-2 ring-[var(--ink)] ring-offset-2 ring-offset-[var(--canvas)]' : '' }}" href="{{ $href($primaryAction) }}">{{ $primaryAction['label'] }}</a>
            </nav>

            <details class="relative lg:hidden">
                <summary class="site-outline cursor-pointer list-none rounded-full border px-4 py-2 text-sm">Menu</summary>
                <nav aria-label="Mobile navigation" class="site-surface site-line absolute right-0 top-12 grid max-h-[80vh] w-72 gap-1 overflow-y-auto rounded-2xl border p-3 shadow-2xl">
                    @foreach ($navigation as $item)
                        <a @if($isCurrent($item)) aria-current="page" @endif class="{{ $mobileLink }} {{ $isCurrent($item) ? $mobileActive : '' }}" href="{{ $href($item) }}">{{ $item['label'] }}</a>
                        @foreach ($item['children'] ?? [] as $child)
                            <a @if($isCurrent($child)) aria-current="page" @endif class="{{ $mobileLink }} site-muted ml-4 py-2 text-sm {{ $isCurrent($child) ? $mobileActive : '' }}" href="{{ $href($child) }}">{{ $child['label'] }}</a>
                        @endforeach
                    @endforeach
                    <a @if($primaryActive) aria-current="page" @endif class="site-button mt-2 rounded-xl px-4 py-3 text-center font-semibold {{ $primaryActive ? 'ring-2 ring-[var(--ink)]' : '' }}" href="{{ $href($primaryAction) }}">{{ $primaryAction['label'] }}</a>
                </nav>
            </details>
        </div>
    </header>

    <main id="content">@yield('content')</main>

    <footer class="site-line border-t">
        <div class="mx-auto max-w-7xl px-5 pt-16 lg:px-8"><x-newsletter-form /></div>
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-16 lg:grid-cols-[1.3fr_2fr] lg:px-8">
            <div class="grid content-start gap-5">
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-xl font-semibold tracking-[-0.04em]"><img class="size-10 rounded-full bg-white object-contain p-1" src="{{ $brandLogo ?? '/images/investai-logo.png' }}" alt=""><span>{{ $brandName ?? 'InvestAI' }}</span></a>
                <p class="site-muted max-w-sm leading-7">{{ $footerDescription ?? 'AI-assisted investment research designed to make goals, risk, evidence and fees easier to understand.' }}</p>
                <div><a class="site-button inline-flex rounded-full px-5 py-2.5 text-sm font-semibold" href="{{ $href($primaryAction) }}">{{ $primaryAction['label'] }}</a></div>
            </div>
            <nav aria-label="Footer navigation" class="grid grid-cols-2 gap-8 sm:grid-cols-3">
                @foreach (config('site.footer') as $heading => $links)
                    <div class="grid content-start gap-4">
                        <p class="text-sm font-semibold">{{ $heading }}</p>
                        <ul class="grid gap-2.5 text-sm">
                            @foreach ($links as $link)
                                <li><a class="site-muted transition hover:text-[var(--accent)]" href="{{ $href($link) }}">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </div>
        <div class="site-line border-t">
            <div class="mx-auto grid max-w-7xl gap-6 px-5 py-8 text-sm lg:grid-cols-[1fr_auto] lg:items-center lg:px-8">
                <div class="grid gap-2">
                    <p class="site-muted max-w-3xl text-xs leading-6">Information on this website is general in nature. It is not investment, financial, legal or tax advice, and nothing here is an offer or recommendation to buy or sell any financial product. Any regulated service will be offered only once the required authorisations are in place.</p>
                    <p class="site-muted text-xs">© {{ date('Y') }} InvestAI. All rights reserved.</p>
                </div>
                <div class="grid gap-4 lg:justify-items-end">
                    <ul class="flex flex-wrap gap-5">
                        @foreach (config('site.legal') as $link)
                            <li><a class="transition hover:text-[var(--accent)]" href="{{ $href($link) }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                    <div class="flex items-center gap-2" aria-label="Choose colour theme">
                        <span class="site-muted mr-1">Theme</span>
                        @foreach (['midnight' => 'Midnight', 'cobalt' => 'Cobalt', 'ivory' => 'Ivory'] as $theme => $label)
                            <button type="button" data-theme-choice="{{ $theme }}" aria-pressed="false" class="site-outline rounded-full border px-3 py-1.5 transition aria-pressed:ring-2 aria-pressed:ring-[var(--accent)]">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
