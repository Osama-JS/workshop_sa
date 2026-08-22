<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_chat_session_id',
        'sender',
        'message',
        'image_path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'ai_chat_session_id');
    }
}
