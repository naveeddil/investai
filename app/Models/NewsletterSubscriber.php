<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['name', 'email', 'monthly_newsletter', 'weekly_tips', 'status', 'confirmation_token_hash', 'manage_token_hash', 'manage_token', 'consent_version', 'consented_at', 'confirmed_at', 'unsubscribed_at'];

    protected $hidden = ['confirmation_token_hash', 'manage_token_hash'];

    protected function casts(): array
    {
        return ['monthly_newsletter' => 'boolean', 'weekly_tips' => 'boolean', 'manage_token' => 'encrypted', 'consented_at' => 'datetime', 'confirmed_at' => 'datetime', 'unsubscribed_at' => 'datetime'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->whereNotNull('confirmed_at')->whereNull('unsubscribed_at');
    }
}
