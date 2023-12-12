<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    public function conversations()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
