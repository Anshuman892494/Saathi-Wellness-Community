<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * ChatMessage Model — stores Saathi AI Companion chat history.
 */
class ChatMessage extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'chat_messages';

    protected $fillable = [
        'user_id',
        'role',      // 'user' or 'assistant'
        'content',
        'persona',   // the persona active during this message
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
