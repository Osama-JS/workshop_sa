<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiDesignIdea extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'category',
        'description_ar',
        'description_en',
        'pinterest_url',
        'image',
        'wood_type',
        'dimensions',
        'estimated_price_range',
        'tags',
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

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : ($this->description_en ?: $this->description_ar);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=800&q=80';
    }
}
