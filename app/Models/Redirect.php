<?php

namespace App\Models;

use Database\Factories\RedirectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    /** @use HasFactory<RedirectFactory> */
    use HasFactory;

    protected $fillable = ['source_path', 'destination', 'http_status', 'enabled'];

    protected function casts(): array
    {
        return ['enabled' => 'boolean'];
    }
}
