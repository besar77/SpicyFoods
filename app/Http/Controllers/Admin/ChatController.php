<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $senders = Chat::select('sender_id')
            ->where('receiver_id', $userId)
            ->where('sender_id', '!=', $userId)
            ->selectRaw('MAX(created_at) as latest_message_sent')
            ->groupBy('sender_id')
            ->orderByDesc('latest_message_sent')
            ->get();
        // dd($senders);

        return view('admin.chat.index', compact('senders'));
    }

    public function getConversation(string $senderId)
    {
        $receiverId = auth()->user()->id;

        Chat::where('sender_id' , $senderId)->where('receiver_id' , $receiverId)
        ->where('seen' , 0)->update(['seen' => 1]);

        $messages = Chat::whereIn('sender_id', [$senderId, $receiverId])
            ->whereIn('receiver_id', [$senderId, $receiverId])
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
        return response($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate(
            [
                'message' => 'required|max:1000',
                'receiver_id' => 'required|integer'
            ],
            [
                'message.required' => 'Ju lutem shkruani nje mesazh.'
            ]
        );

        $chat = new Chat();
        $chat->sender_id = auth()->user()->id;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message;
        $chat->save();

        $avatar = auth()->user()->avatar;
        // Broadcast the message to the specified channel
        broadcast(new ChatEvent($request->message, $avatar, $request->receiver_id, auth()->user()->id))->toOthers();

        return response(['status' => 'Success','msgId' => $request->msg_temp_id]);
    }
}