<?php

namespace App\Models;

use Database\Factories\ConsultationRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    /** @use HasFactory<ConsultationRequestFactory> */
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'company', 'preferred_contact', 'preferred_time', 'topic', 'message', 'consent', 'status', 'submitted_at', 'ip_hash'];

    protected function casts(): array
    {
        return ['consent' => 'boolean', 'submitted_at' => 'datetime'];
    }
}
