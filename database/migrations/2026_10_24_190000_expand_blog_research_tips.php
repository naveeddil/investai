<?php

use Database\Seeders\ResearchTipsSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        app(ResearchTipsSeeder::class)->run();
    }

    public function down(): void
    {
        // Preserve published editorial content and subsequent CMS changes.
    }
};
