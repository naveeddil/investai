<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Switch sites still on the old Midnight default to Ivory. A theme chosen
     * deliberately in the CMS (Cobalt) is left alone. SiteSetting stores its
     * values JSON-encoded, so the plain string form is matched as well.
     */
    public function up(): void
    {
        DB::table('site_settings')
            ->where('key', 'appearance.theme')
            ->whereIn('value', [json_encode('midnight'), 'midnight'])
            ->update(['value' => json_encode('ivory'), 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'appearance.theme')
            ->whereIn('value', [json_encode('ivory'), 'ivory'])
            ->update(['value' => json_encode('midnight'), 'updated_at' => now()]);
    }
};
