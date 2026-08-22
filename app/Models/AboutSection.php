<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'title_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_en',
        'content_ar',
        'content_en',
        'image',
        'meta_data',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : ($this->title_en ?: $this->title_ar);
    }

    public function getSubtitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->subtitle_ar : ($this->subtitle_en ?: $this->subtitle_ar);
    }

    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : ($this->content_en ?: $this->content_ar);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
