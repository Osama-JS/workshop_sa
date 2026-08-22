<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_en',
        'description_ar',
        'description_en',
        'btn_text_ar',
        'btn_text_en',
        'btn_url',
        'secondary_btn_text_ar',
        'secondary_btn_text_en',
        'secondary_btn_url',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_ar;
    }

    public function getSubtitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"subtitle_{$locale}"} ?? $this->subtitle_ar;
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_ar;
    }

    public function getBtnTextAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"btn_text_{$locale}"} ?? $this->btn_text_ar;
    }

    public function getSecondaryBtnTextAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"secondary_btn_text_{$locale}"} ?? $this->secondary_btn_text_ar;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1920&q=80';
    }
}
