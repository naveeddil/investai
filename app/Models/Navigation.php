<?php

namespace App\Models;

use Database\Factories\NavigationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Navigation extends Model
{
    /** @use HasFactory<NavigationFactory> */
    use HasFactory;

    protected $fillable = ['name', 'key'];

    public function items(): HasMany
    {
        return $this->hasMany(NavigationItem::class)->orderBy('sort_order')->orderBy('id');
    }
}
