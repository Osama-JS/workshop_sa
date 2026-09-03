<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'visitor_ip',
        'user_id',
        'user_name',
        'user_phone',
        'order_id',
        'total_messages',
        'tokens_used_today',
        'messages_count_today',
        'last_activity_date',
    ];

    protected $casts = [
        'tokens_used_today' => 'integer',
        'messages_count_today' => 'integer',
        'last_activity_date' => 'date',
    ];

    /**
     * Get or calculate remaining daily message quota.
     */
    public function getDailyQuotaInfo(): array
    {
        $maxDailyMessages = (int) Setting::get('ai_daily_message_limit', '25');
        if ($maxDailyMessages <= 0) $maxDailyMessages = 25;

        $today = now()->toDateString();
        if ($this->last_activity_date?->toDateString() !== $today) {
            $this->update([
                'messages_count_today' => 0,
                'tokens_used_today' => 0,
                'last_activity_date' => $today,
            ]);
            $usedToday = 0;
        } else {
            $usedToday = $this->messages_count_today;
        }

        $remaining = max(0, $maxDailyMessages - $usedToday);
        $percent = $maxDailyMessages > 0 ? round(($remaining / $maxDailyMessages) * 100) : 100;

        return [
            'max_limit' => $maxDailyMessages,
            'used_today' => $usedToday,
            'remaining' => $remaining,
            'percent' => $percent,
            'is_exhausted' => $remaining <= 0,
        ];
    }

    /**
     * Increment today's usage count.
     */
    public function recordUsage(int $tokensCount = 100): void
    {
        $today = now()->toDateString();
        if ($this->last_activity_date?->toDateString() !== $today) {
            $this->update([
                'messages_count_today' => 1,
                'tokens_used_today' => $tokensCount,
                'last_activity_date' => $today,
                'total_messages' => $this->total_messages + 2,
            ]);
        } else {
            $this->increment('messages_count_today', 1);
            $this->increment('tokens_used_today', $tokensCount);
            $this->increment('total_messages', 2);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(CustomOrder::class, 'order_id');
    }

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class, 'ai_chat_session_id')->orderBy('created_at', 'asc');
    }
}
