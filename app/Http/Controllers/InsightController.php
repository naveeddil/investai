<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class InsightController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()->published()->latest('published_at')->paginate(9);

        return view('insights.index', compact('articles'));
    }

    public function show(string $slug): View
    {
        $article = Article::query()->published()->where('slug', $slug)->firstOrFail();

        return view('insights.show', compact('article'));
    }
}
