<?php

use Database\Seeders\ProductWebsiteSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Apply the approved product-site content once during deployment.
     */
    public function up(): void
    {
        app(ProductWebsiteSeeder::class)->run();
    }

    /**
     * Content migrations are intentionally not destructive on rollback.
     */
    public function down(): void
    {
        // Preserve any CMS edits made after deployment.
    }
};
