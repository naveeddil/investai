<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class PublicPageController extends Controller
{
    public function home(): View
    {
        return $this->render('home');
    }

    public function show(string $slug): View
    {
        return $this->render($slug);
    }

    private function render(string $slug): View
    {
        $page = Page::query()->published()->where('slug', $slug)->firstOrFail();

        $siteTheme = SiteSetting::read('appearance.theme', 'ivory');

        if (! in_array($siteTheme, ['midnight', 'cobalt', 'ivory'], true)) {
            $siteTheme = 'ivory';
        }

        $teamMembers = TeamMember::query()->where('is_visible', true)->orderBy('sort_order')->orderBy('id')->get();

        $latestInsights = $this->pageHasBlock($page, 'latest_insights')
            ? Article::query()->published()->latest('published_at')->limit(3)->get()
            : collect();

        return view('pages.show', compact('page', 'siteTheme', 'teamMembers', 'latestInsights'));
    }

    private function pageHasBlock(Page $page, string $type): bool
    {
        return collect($page->blocks ?? [])->contains(fn (array $block): bool => ($block['type'] ?? null) === $type);
    }
}
