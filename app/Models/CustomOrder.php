<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'service_id',
        'customer_name',
        'customer_phone',
        'customer_whatsapp',
        'customer_email',
        'wood_type',
        'dimensions',
        'budget_range',
        'description',
        'attachments',
        'status',
        'admin_notes',
        'is_notified',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_notified' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $locale = app()->getLocale();
        $statuses = [
            'pending' => ['ar' => 'قيد الانتظار', 'en' => 'Pending', 'badge' => 'warning'],
            'in_review' => ['ar' => 'قيد المراجعة', 'en' => 'In Review', 'badge' => 'info'],
            'contacted' => ['ar' => 'تم التواصل', 'en' => 'Contacted', 'badge' => 'primary'],
            'in_progress' => ['ar' => 'قيد التنفيذ', 'en' => 'In Progress', 'badge' => 'secondary'],
            'completed' => ['ar' => 'مكتمل', 'en' => 'Completed', 'badge' => 'success'],
            'cancelled' => ['ar' => 'ملغي', 'en' => 'Cancelled', 'badge' => 'danger'],
        ];

        return $statuses[$this->status][$locale] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'in_review' => 'bg-blue-100 text-blue-800 border-blue-300',
            'contacted' => 'bg-purple-100 text-purple-800 border-purple-300',
            'in_progress' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getWhatsappLinkAttribute(): ?string
    {
        $phone = $this->customer_whatsapp ?: $this->customer_phone;
        if (!$phone) return null;

        // Clean phone number
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        // If local number starting with 05 in SA, convert to 9665
        if (str_starts_with($cleanPhone, '05')) {
            $cleanPhone = '966' . substr($cleanPhone, 1);
        }

        $text = urlencode("مرحباً {$this->customer_name}، نتواصل معك بخصوص طلبك لتفصيل الأعمال الخشبية رقم ({$this->order_number}).");
        return "https://wa.me/{$cleanPhone}?text={$text}";
    }
}
