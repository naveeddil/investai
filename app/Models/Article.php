<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected $fillable = ['title', 'slug', 'kind', 'status', 'excerpt', 'body', 'featured_image', 'featured_image_alt', 'source_url', 'generated_by_ai', 'review_notes', 'reviewed_by', 'reviewed_at', 'published_at', 'scheduled_for', 'weekly_emailed_at'];

    protected function casts(): array
    {
        return ['generated_by_ai' => 'boolean', 'reviewed_at' => 'datetime', 'published_at' => 'datetime', 'scheduled_for' => 'datetime', 'weekly_emailed_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            if ($article->status === 'published' && (! $article->reviewed_at || ! $article->published_at)) {
                throw ValidationException::withMessages([
                    'status' => 'Published articles require both review and publication dates.',
                ]);
            }

            if ($article->reviewed_at && ! $article->reviewed_by && auth()->check()) {
                $article->reviewed_by = auth()->id();
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('reviewed_at')->where('published_at', '<=', now());
    }
}
