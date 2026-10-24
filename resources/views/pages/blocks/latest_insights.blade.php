<div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 lg:px-8 lg:py-28">
    <div class="scroll-reveal flex flex-wrap items-end justify-between gap-6">
        <div class="grid max-w-2xl gap-4">
            <p class="eyebrow">{{ $data['eyebrow'] ?? 'Insights' }}</p>
            <h2 class="section-title font-semibold">{{ $data['heading'] ?? 'Latest insights' }}</h2>
            @if (! empty($data['intro']))<p class="site-muted text-lg leading-8">{{ $data['intro'] }}</p>@endif
        </div>
        <a class="btn-secondary inline-flex items-center gap-2 rounded-full px-5 py-3 font-semibold" href="{{ route('insights.index') }}">All insights <x-glyph name="arrow-right" class="size-5" /></a>
    </div>
    <div class="grid gap-5 md:grid-cols-3">
        @foreach ($latestInsights as $article)
            <article class="scroll-reveal card card-hover group relative grid content-between overflow-hidden">
                @if ($article->featured_image)<figure class="aspect-[16/10] overflow-hidden"><img class="image-wash size-full object-cover transition duration-500 group-hover:scale-105" src="{{ str_starts_with($article->featured_image, '/') ? $article->featured_image : '/'.$article->featured_image }}" alt="{{ $article->featured_image_alt }}" loading="lazy"></figure>@endif
                <div class="grid gap-4 p-7">
                    <p class="eyebrow !tracking-[.2em]">{{ $article->published_at->format('j M Y') }}</p>
                    <h3 class="text-xl font-semibold tracking-tight"><a class="after:absolute after:inset-0" href="{{ route('insights.show', $article->slug) }}">{{ $article->title }}</a></h3>
                    <p class="site-muted leading-7">{{ $article->excerpt }}</p>
                </div>
            </article>
        @endforeach
    </div>
</div>
