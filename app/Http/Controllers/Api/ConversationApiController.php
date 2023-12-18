<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationApiController extends Controller
{
    public function index() {

        $user = Auth::user();
        $conversations = $user->conversations;

        return response()->json(['conversations' => $conversations]);
    }
    public function show($id) {

        $conversation = Conversation::findOrFail($id);

        $messages = $conversation->messages;

        return response()->json(['conversation' => $conversation, 'messages' => $messages]);
    }
    public function store() {

        $user = Auth::user();

        $user->conversations()->create([
            'name' => "Nouvelle conversation",
        ]);

        return response("", 201);
    }

    public function destroy($id) {

        $conversation = Conversation::findOrFail($id);

        $conversation->delete();

        return response("", 204);
    }

    public function updateName($id, Request $request) {

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $conversation = Conversation::findOrFail($id);

        $conversation->update([
            'name' => $request->input('content'),
        ]);

        return response()->json("",204);
    }

}
