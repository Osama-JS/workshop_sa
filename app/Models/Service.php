<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'short_desc_ar',
        'short_desc_en',
        'content_ar',
        'content_en',
        'icon',
        'image',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function customOrders()
    {
        return $this->hasMany(CustomOrder::class);
    }

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : ($this->title_en ?: $this->title_ar);
    }

    public function getShortDescAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->short_desc_ar : ($this->short_desc_en ?: $this->short_desc_ar);
    }

    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : ($this->content_en ?: $this->content_ar);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-service.jpg');
    }
}
