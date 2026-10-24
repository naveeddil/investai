<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Switch sites still on the old Midnight default to Ivory. A theme chosen
     * deliberately in the CMS (Cobalt) is left alone.
     */
    public function up(): void
    {
        DB::table('site_settings')
            ->where('key', 'appearance.theme')
            ->where('value', 'midnight')
            ->update(['value' => 'ivory', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'appearance.theme')
            ->where('value', 'ivory')
            ->update(['value' => 'midnight', 'updated_at' => now()]);
    }
};
