<?php

use Database\Seeders\ProductWebsiteSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Apply the approved consultation-led copy once during deployment.
     */
    public function up(): void
    {
        app(ProductWebsiteSeeder::class)->run();
    }

    public function down(): void
    {
        // Preserve subsequent CMS edits.
    }
};
