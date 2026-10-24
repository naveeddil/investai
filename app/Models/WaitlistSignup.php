<?php

namespace App\Models;

use Database\Factories\WaitlistSignupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitlistSignup extends Model
{
    /** @use HasFactory<WaitlistSignupFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'audience_type', 'consent', 'consent_version',
        'consented_at', 'status', 'submitted_at', 'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'consent' => 'boolean',
            'consented_at' => 'datetime',
            'submitted_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }
}
