<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PrivateMessageSent implements ShouldBroadcastNow
{
    public function __construct(
        public Message $message,
        public int $conversationId
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->conversationId);
    }

    public function broadcastAs(): string
    {
        return 'PrivateMessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => array_merge($this->message->toArray(), [
                'tick_status' => $this->message->tick_status,
            ]),
        ];
    }
}
