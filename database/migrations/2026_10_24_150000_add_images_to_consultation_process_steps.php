<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const IMAGES = [
        'Request' => ['/images/editorial/request-desk.jpg', 'Open notebook, phone and laptop on a desk'],
        'Conversation' => ['/images/editorial/conversation-meeting.jpg', 'Two people talking across a table with notes and a tablet'],
        'Proposal' => ['/images/editorial/proposal-writing.jpg', 'Fountain pen writing on lined paper'],
    ];

    /**
     * Add pictures to the "What to expect" steps on the consultation page
     * without re-running the content seeder, so other CMS edits are kept.
     * Steps that already have an image are left alone.
     */
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'consultation')->first(['id', 'blocks']);
        $blocks = $page ? json_decode((string) $page->blocks, true) : null;

        if (! is_array($blocks)) {
            return;
        }

        foreach ($blocks as &$block) {
            if (($block['type'] ?? null) !== 'process' || ($block['data']['heading'] ?? null) !== 'From request to recommendation.') {
                continue;
            }

            foreach ($block['data']['steps'] as &$step) {
                [$image, $alt] = self::IMAGES[$step['heading'] ?? ''] ?? [null, null];

                if ($image !== null && empty($step['image'])) {
                    $step['image'] = $image;
                    $step['image_alt'] = $alt;
                }
            }
            unset($step);
        }
        unset($block);

        DB::table('pages')->where('id', $page->id)->update(['blocks' => json_encode($blocks), 'updated_at' => now()]);
    }

    public function down(): void
    {
        //
    }
};
