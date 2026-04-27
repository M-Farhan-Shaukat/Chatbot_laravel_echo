<?php

namespace App\Http\Controllers;

use App\Events\MessageDelivered;
use App\Events\MessageRead;
use App\Events\PrivateMessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ChatController extends Controller
{
    public function app()
    {
        $authId = auth()->id();

        $conversations = Conversation::where('user_one', $authId)
            ->orWhere('user_two', $authId)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->orderByDesc('updated_at')
            ->get();

        // Attach unread count per conversation
        $conversations->each(function ($conv) use ($authId) {
            $conv->unread_count = Message::where('conversation_id', $conv->id)
                ->where('sender_id', '!=', $authId)
                ->where('is_read', false)
                ->count();
        });

        return view('app', compact('conversations'));
    }

    public function chatData($userId)
    {
        $authId = auth()->id();

        $conversation = Conversation::firstOrCreate([
            'user_one' => min($authId, $userId),
            'user_two' => max($authId, $userId),
        ]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('id')
            ->get();

        // Mark messages sent TO me as delivered (if not already)
        $undelivered = Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $authId)
            ->whereNull('delivered_at')
            ->get();

        foreach ($undelivered as $msg) {
            $msg->update(['delivered_at' => Carbon::now()]);
            broadcast(new MessageDelivered($conversation->id, $msg->id))->toOthers();
        }

        return response()->json([
            'conversation' => $conversation,
            'messages'     => $messages->append('tick_status'),
        ]);
    }

    public function chats()
    {
        $authId = auth()->id();
        $conversations = Conversation::where('user_one', $authId)
            ->orWhere('user_two', $authId)
            ->get();
        return view('chats', compact('conversations'));
    }

    public function open($userId)
    {
        return redirect('/app?open=' . $userId);
    }

    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:5000']);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id'       => auth()->id(),
            'message'         => $request->message,
            'is_read'         => false,
            'delivered_at'    => null,
        ]);

        // Touch conversation so sidebar re-orders
        Conversation::where('id', $request->conversation_id)
            ->touch();

        broadcast(new PrivateMessageSent($message, $request->conversation_id))->toOthers();

        return response()->json($message->append('tick_status'));
    }

    public function markRead($conversationId)
    {
        $authId = auth()->id();

        $updated = Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'delivered_at' => \DB::raw('COALESCE(delivered_at, NOW())')]);

        if ($updated) {
            broadcast(new MessageRead((int) $conversationId, $authId))->toOthers();
        }

        return response()->json(['ok' => true]);
    }
}
