<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name_ar',
        'client_name_en',
        'client_position_ar',
        'client_position_en',
        'company_ar',
        'company_en',
        'rating',
        'comment_ar',
        'comment_en',
        'client_avatar',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getClientNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->client_name_ar : ($this->client_name_en ?: $this->client_name_ar);
    }

    public function getClientPositionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->client_position_ar : ($this->client_position_en ?: $this->client_position_ar);
    }

    public function getCompanyAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->company_ar : ($this->company_en ?: $this->company_ar);
    }

    public function getCommentAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->comment_ar : ($this->comment_en ?: $this->comment_ar);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->client_avatar && file_exists(public_path('storage/' . $this->client_avatar))) {
            return asset('storage/' . $this->client_avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->client_name_ar) . '&background=D4AF37&color=fff&size=100';
    }
}
