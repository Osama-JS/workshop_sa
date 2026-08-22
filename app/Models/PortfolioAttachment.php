<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'file_path',
        'file_name',
        'media_type',
        'file_size',
        'sort_order',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
