<?php

use Database\Seeders\InsightSeeder;
use Database\Seeders\ProductWebsiteSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        app(ProductWebsiteSeeder::class)->run();
        app(InsightSeeder::class)->run();
    }

    public function down(): void
    {
        // Preserve subsequent CMS and editorial changes.
    }
};
