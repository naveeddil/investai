<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Page::query()->where('slug', 'home')->update([
            'seo_description' => 'Explore clearer investment decisions with AI-assisted research. Understand goals, risk, fees and long-term scenarios in pounds.',
            'open_graph_description' => 'Explore clearer investment decisions with AI-assisted research. Understand goals, risk, fees and long-term scenarios in pounds.',
        ]);
    }

    public function down(): void
    {
        // Preserve subsequent SEO and CMS changes.
    }
};
