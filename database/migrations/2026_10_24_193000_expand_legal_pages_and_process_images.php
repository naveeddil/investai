<?php

use Database\Seeders\LegalContentSeeder;
use Database\Seeders\ProcessImageSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        app(LegalContentSeeder::class)->run();
        app(ProcessImageSeeder::class)->run();
    }

    public function down(): void
    {
        // Preserve subsequent CMS and editorial changes.
    }
};
