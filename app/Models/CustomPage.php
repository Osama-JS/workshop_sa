<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'content_ar',
        'content_en',
        'placement',
        'meta_title_ar',
        'meta_title_en',
        'meta_desc_ar',
        'meta_desc_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : ($this->title_en ?: $this->title_ar);
    }

    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : ($this->content_en ?: $this->content_ar);
    }

    public function getMetaTitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->meta_title_ar : ($this->meta_title_en ?: $this->meta_title_ar);
    }

    public function getMetaDescAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->meta_desc_ar : ($this->meta_desc_en ?: $this->meta_desc_ar);
    }
}
