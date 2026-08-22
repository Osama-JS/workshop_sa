<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label_ar',
        'label_en',
    ];

    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return static::pluck('value', 'key')->all();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value, $group = 'general', $type = 'text'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );

        Cache::forget('site_settings');

        return $setting;
    }

    public static function getAllGrouped(): array
    {
        return static::all()->groupBy('group')->toArray();
    }
}
