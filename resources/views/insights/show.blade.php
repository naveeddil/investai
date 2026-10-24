@extends('layouts.public', ['metaTitle' => $article->title.' | InvestAI Blog', 'metaDescription' => $article->excerpt])

@section('content')
    <article>
        <header class="site-line border-b"><div class="mx-auto grid max-w-4xl gap-7 px-5 py-20 lg:py-28"><a class="site-accent text-sm font-semibold" href="{{ route('insights.index') }}">← All blog posts</a><p class="site-muted text-sm">{{ $article->published_at->format('j F Y') }} · Tips & education</p><h1 class="section-title font-semibold">{{ $article->title }}</h1><p class="site-muted text-xl leading-8">{{ $article->excerpt }}</p></div></header>
        @if ($article->featured_image)<figure class="mx-auto max-w-6xl px-5 pt-16"><img class="site-line max-h-[38rem] w-full rounded-[2rem] border object-cover" src="{{ str_starts_with($article->featured_image, '/') ? $article->featured_image : '/'.$article->featured_image }}" alt="{{ $article->featured_image_alt }}"></figure>@endif
        <div class="mx-auto grid max-w-4xl gap-8 px-5 py-16"><div class="site-muted whitespace-pre-line text-lg leading-9">{{ $article->body }}</div><aside class="site-surface site-line rounded-2xl border p-6 text-sm leading-6"><strong>Important:</strong> This article is general information, not personalised investment, financial, legal or tax advice. Investing involves risk, including possible loss of capital.</aside></div>
    </article>
@endsection
