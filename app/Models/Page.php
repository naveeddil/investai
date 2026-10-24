<?php

namespace App\Models;

use App\Enums\PageStatus;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'navigation_label', 'description', 'page_type', 'status',
        'is_system_page', 'deletion_protected', 'show_in_navigation', 'show_in_footer',
        'index_by_search_engines', 'blocks', 'featured_media_id', 'seo_title',
        'seo_description', 'canonical_url', 'open_graph_title', 'open_graph_description',
        'open_graph_media_id', 'robots_follow', 'published_at', 'scheduled_publish_at',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => PageStatus::class,
            'blocks' => 'array',
            'is_system_page' => 'boolean',
            'deletion_protected' => 'boolean',
            'show_in_navigation' => 'boolean',
            'show_in_footer' => 'boolean',
            'index_by_search_engines' => 'boolean',
            'robots_follow' => 'boolean',
            'published_at' => 'datetime',
            'scheduled_publish_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', PageStatus::Published)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class)->latest('version_number');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
