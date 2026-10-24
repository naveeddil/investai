<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CoreContentSeeder::class);
        $this->call(ConsultancyContentSeeder::class);
        $this->call(ProductWebsiteSeeder::class);
        $this->call(InsightSeeder::class);
        $this->call(ResearchTipsSeeder::class);
        $this->call(LegalContentSeeder::class);
        $this->call(ProcessImageSeeder::class);
    }
}
