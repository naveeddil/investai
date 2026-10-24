<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class ProcessImageSeeder extends Seeder
{
    private const IMAGES = [
        ['/images/editorial/request-desk.jpg', 'Notebook, phone and laptop representing preparation and discovery'],
        ['/images/editorial/conversation-meeting.jpg', 'People in discussion representing collaborative review'],
        ['/images/editorial/proposal-writing.jpg', 'Writing on paper representing a documented decision'],
        ['/images/editorial/glass-architecture.jpg', 'Structured architecture representing a clear delivery framework'],
        ['/images/editorial/reflected-architecture.jpg', 'Reflections representing review and continuous improvement'],
    ];

    public function run(): void
    {
        Page::query()->whereNotNull('blocks')->each(function (Page $page): void {
            $blocks = $page->blocks;
            $changed = false;

            foreach ($blocks as &$block) {
                if (($block['type'] ?? null) !== 'process') {
                    continue;
                }

                if (empty($block['data']['steps']) || ! is_array($block['data']['steps'])) {
                    continue;
                }

                foreach ($block['data']['steps'] as $index => &$step) {
                    if (! empty($step['image'])) {
                        continue;
                    }

                    [$step['image'], $step['image_alt']] = self::IMAGES[$index % count(self::IMAGES)];
                    $changed = true;
                }
                unset($step);
            }
            unset($block);

            if ($changed) {
                $page->update(['blocks' => $blocks]);
            }
        });
    }
}
