<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'service_id',
        'description_ar',
        'description_en',
        'client_name',
        'completion_date',
        'location',
        'main_image',
        'video_url',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function attachments()
    {
        return $this->hasMany(PortfolioAttachment::class)->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(PortfolioAttachment::class)->where('media_type', 'image')->orderBy('sort_order');
    }

    public function videos()
    {
        return $this->hasMany(PortfolioAttachment::class)->where('media_type', 'video')->orderBy('sort_order');
    }

    public function pdfs()
    {
        return $this->hasMany(PortfolioAttachment::class)->where('media_type', 'pdf')->orderBy('sort_order');
    }

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : ($this->title_en ?: $this->title_ar);
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : ($this->description_en ?: $this->description_ar);
    }

    public function getMainImageUrlAttribute(): string
    {
        if ($this->main_image && file_exists(public_path('storage/' . $this->main_image))) {
            return asset('storage/' . $this->main_image);
        }
        return asset('images/default-portfolio.jpg');
    }
}
