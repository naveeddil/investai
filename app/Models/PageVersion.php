<?php

namespace App\Models;

use Database\Factories\PageVersionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVersion extends Model
{
    /** @use HasFactory<PageVersionFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['page_id', 'version_number', 'action', 'snapshot', 'user_id', 'created_at'];

    protected function casts(): array
    {
        return ['snapshot' => 'array', 'created_at' => 'datetime'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
