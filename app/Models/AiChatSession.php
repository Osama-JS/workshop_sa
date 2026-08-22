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
    ];

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
