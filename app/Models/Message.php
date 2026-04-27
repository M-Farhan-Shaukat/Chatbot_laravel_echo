<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'sender_id', 'message', 'is_read', 'delivered_at'];

    protected $casts = [
        'is_read'      => 'boolean',
        'delivered_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // tick status: 'sent' | 'delivered' | 'read'
    public function getTickStatusAttribute(): string
    {
        if ($this->is_read)      return 'read';
        if ($this->delivered_at) return 'delivered';
        return 'sent';
    }
}
