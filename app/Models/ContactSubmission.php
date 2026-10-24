<?php

namespace App\Models;

use Database\Factories\ContactSubmissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    /** @use HasFactory<ContactSubmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'company', 'enquiry_type', 'message', 'status',
        'submitted_at', 'status_updated_at', 'ip_hash',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'status_updated_at' => 'datetime',
        ];
    }
}
