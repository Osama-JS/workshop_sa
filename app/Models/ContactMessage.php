<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'read_at',
        'reply_notes',
        'replied_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function getWhatsappLinkAttribute(): ?string
    {
        if (!$this->phone) return null;

        $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone);
        if (str_starts_with($cleanPhone, '05')) {
            $cleanPhone = '966' . substr($cleanPhone, 1);
        }

        $text = urlencode("مرحباً {$this->name}، شكراً لتواصلك معنا عبر موقع ورشة الأعمال الخشبية.");
        return "https://wa.me/{$cleanPhone}?text={$text}";
    }
}
