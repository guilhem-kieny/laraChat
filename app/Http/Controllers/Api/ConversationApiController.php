<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationApiController extends Controller
{
    public function show($id) {

        $conversation = Conversation::findOrFail($id);

        $messages = $conversation->messages;

        return response()->json(['conversation' => $conversation, 'messages' => $messages]);
    }

    public function store($id, Request $request) {

        $conversation = Conversation::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
        ]);

        $message = $conversation->messages()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
            'conversation_id' => $id,
        ]);

        broadcast(new NewMessage($message))->toOthers();

        return response()->json(['message' => $message]);
    }
}
