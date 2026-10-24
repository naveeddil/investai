@extends('layouts.public', ['metaTitle' => 'Blog | InvestAI', 'metaDescription' => 'Professional tips on investing, risk, fees, research and responsible financial decision-making.'])

@section('content')
    <section class="site-line border-b">
        <div class="mx-auto grid max-w-7xl gap-7 px-5 py-20 lg:px-8 lg:py-28">
            <p class="site-accent text-xs font-semibold uppercase tracking-[.28em]">InvestAI blog</p>
            <h1 class="display-title max-w-5xl font-semibold">Practical tips for better financial decisions.</h1>
            <p class="site-muted max-w-3xl text-lg leading-8">Professional guidance on research habits, risk awareness, fees and long-term decision-making. Every article is educational and reviewed before publication.</p>
        </div>
    </section>
    <section class="site-soft">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 py-20 md:grid-cols-2 lg:grid-cols-3 lg:px-8">
            @forelse ($articles as $article)
                <article class="site-surface site-line group grid min-h-80 content-between overflow-hidden rounded-3xl border">
                    @if ($article->featured_image)<a href="{{ route('insights.show', $article->slug) }}" class="block aspect-[16/10] overflow-hidden"><img class="image-wash size-full object-cover transition duration-500 group-hover:scale-105" src="{{ str_starts_with($article->featured_image, '/') ? $article->featured_image : '/'.$article->featured_image }}" alt="{{ $article->featured_image_alt }}" loading="lazy"></a>@endif
                    <div class="grid gap-8 p-7"><div class="grid gap-4"><p class="site-accent text-xs font-semibold uppercase tracking-widest">{{ $article->published_at->format('j M Y') }}</p><h2 class="text-2xl font-semibold tracking-tight"><a href="{{ route('insights.show', $article->slug) }}">{{ $article->title }}</a></h2><p class="site-muted leading-7">{{ $article->excerpt }}</p></div><a class="font-semibold" href="{{ route('insights.show', $article->slug) }}">Read post <span aria-hidden="true">→</span></a></div>
                </article>
            @empty
                <div class="site-surface site-line rounded-3xl border p-8 md:col-span-2 lg:col-span-3"><h2 class="text-2xl font-semibold">The first weekly insight is being prepared.</h2><p class="site-muted mt-3">Each article is reviewed by a person before publication.</p></div>
            @endforelse
        </div>
        <div class="mx-auto max-w-7xl px-5 pb-20 lg:px-8">{{ $articles->links() }}</div>
    </section>
    <section class="site-line border-t">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 py-16 lg:grid-cols-[1fr_auto] lg:items-end lg:px-8 lg:py-24">
            <div class="grid max-w-3xl gap-4"><p class="eyebrow">Your feedback</p><h2 class="section-title font-semibold">What should we explain next?</h2><p class="site-muted text-lg leading-8">Suggest a topic, share feedback on an article or tell us which financial concept deserves a clearer explanation. Please do not include personal account details.</p></div>
            <a class="site-button inline-flex justify-center rounded-full px-6 py-3.5 font-semibold" href="{{ route('pages.show', 'contact') }}?type=feedback">Share feedback</a>
        </div>
    </section>
@endsection
