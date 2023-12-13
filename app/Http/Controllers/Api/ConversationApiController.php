<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $message = $conversation->messages()->create([
            'content' => $request->input('content'),
            'user_id' => $user->id,
            'conversation_id' => $id,
        ]);


        return response()->json(['message' => $message]);
    }
}
