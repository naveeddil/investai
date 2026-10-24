<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * List every published, indexable page and article.
     */
    public function __invoke(): Response
    {
        $pages = Page::query()
            ->published()
            ->where('index_by_search_engines', true)
            ->orderBy('slug')
            ->get(['slug', 'updated_at']);

        $articles = Article::query()->published()->latest('published_at')->get(['slug', 'updated_at']);

        $entries = collect([['loc' => route('insights.index'), 'lastmod' => $articles->max('updated_at')]])
            ->merge($pages->map(fn (Page $page): array => [
                'loc' => $page->slug === 'home' ? route('home') : route('pages.show', $page->slug),
                'lastmod' => $page->updated_at,
            ]))
            ->merge($articles->map(fn (Article $article): array => [
                'loc' => route('insights.show', $article->slug),
                'lastmod' => $article->updated_at,
            ]));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".view('sitemap', ['entries' => $entries])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
