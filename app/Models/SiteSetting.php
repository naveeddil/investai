<?php

namespace App\Models;

use Database\Factories\SiteSettingFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SiteSetting extends Model
{
    /** @use HasFactory<SiteSettingFactory> */
    use HasFactory;

    protected $fillable = ['group', 'key', 'value', 'type', 'updated_by'];

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): mixed => $value === null ? null : (json_decode($value, true) ?? $value),
            set: fn (mixed $value): string => json_encode($value, JSON_THROW_ON_ERROR),
        );
    }

    public static function read(string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('key', $key)->value('value');

        if ($value === null) {
            return $default;
        }

        return json_decode($value, true) ?? $value;
    }

    public static function write(string $key, mixed $value, string $group = 'website', string $type = 'string'): void
    {
        static::query()->updateOrCreate(['key' => $key], ['group' => $group, 'value' => $value, 'type' => $type, 'updated_by' => auth()->id()]);
    }

    public static function writeSecret(string $key, ?string $value): void
    {
        if (filled($value)) {
            static::write($key, Crypt::encryptString($value), 'email', 'secret');
        }
    }

    public static function readSecret(string $key): ?string
    {
        $value = static::read($key);
        if (! is_string($value) || $value === '') {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
