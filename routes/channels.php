<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return Conversation::where('id', $id)
        ->where(function ($q) use ($user) {
            $q->where('user_one', $user->id)
                ->orWhere('user_two', $user->id);
        })
        ->exists();
});

// Presence channel — tracks who is online
Broadcast::channel('online', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
