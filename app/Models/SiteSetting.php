<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function bankAccount(): array
    {
        return [
            'bank_name' => static::get('bank_name', 'BCA'),
            'account_number' => static::get('bank_account_number', ''),
            'account_name' => static::get('bank_account_name', ''),
        ];
    }
}
