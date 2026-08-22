<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'name_en',
        'group',
        'group_ar',
        'group_en',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDisplayGroupAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->group_ar : $this->group_en;
    }
}
