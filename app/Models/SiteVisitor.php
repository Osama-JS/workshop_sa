<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'ip_address',
        'device_type',
        'browser',
        'platform',
        'country',
        'country_code',
        'city',
        'landing_page',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'page_views_count',
        'first_visited_at',
        'last_visited_at',
    ];

    protected $casts = [
        'first_visited_at' => 'datetime',
        'last_visited_at' => 'datetime',
        'page_views_count' => 'integer',
    ];

    public function pageViews()
    {
        return $this->hasMany(PageView::class);
    }
}
