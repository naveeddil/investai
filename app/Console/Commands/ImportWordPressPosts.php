<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

#[Signature('content:import-wordpress {--url=https://investai.business}')]
#[Description('Import public WordPress posts as unpublished legacy drafts')]
class ImportWordPressPosts extends Command
{
    public function handle(): int
    {
        $baseUrl = rtrim((string) $this->option('url'), '/');
        $response = Http::timeout(30)->retry(2, 500)->get($baseUrl.'/wp-json/wp/v2/posts', [
            'per_page' => 100,
            '_embed' => 1,
        ]);

        if ($response->failed()) {
            $this->error('WordPress returned HTTP '.$response->status().'.');

            return self::FAILURE;
        }

        foreach ($response->json() as $post) {
            $body = Str::of((string) data_get($post, 'content.rendered'))
                ->replaceMatches('/<script\b[^>]*>(.*?)<\/script>/is', '')
                ->replaceMatches('/<style\b[^>]*>(.*?)<\/style>/is', '')
                ->stripTags()
                ->replace('&nbsp;', ' ')
                ->squish()
                ->toString();

            Article::updateOrCreate(['source_url' => data_get($post, 'link')], [
                'title' => html_entity_decode(strip_tags((string) data_get($post, 'title.rendered')), ENT_QUOTES | ENT_HTML5),
                'slug' => 'legacy-'.data_get($post, 'slug'),
                'kind' => 'legacy',
                'status' => 'draft',
                'excerpt' => Str::limit($body, 240),
                'body' => $body,
                'review_notes' => 'Imported from the former WordPress site. Template/demo content—verify ownership, relevance and accuracy before considering publication.',
                'published_at' => null,
                'reviewed_at' => null,
            ]);
        }

        $this->info('Legacy posts imported as drafts. Nothing was published.');

        return self::SUCCESS;
    }
}
