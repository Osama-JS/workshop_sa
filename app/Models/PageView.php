<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'site_visitor_id',
        'session_id',
        'url',
        'route_name',
        'page_title',
        'referrer',
        'ip_address',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(SiteVisitor::class, 'site_visitor_id');
    }
}
