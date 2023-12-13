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
    public function store() {

        $user = Auth::user();

        $conversation = $user->conversations()->create([
            'name' => "create-conv",
        ]);

        return response()->json(['conversation' => $conversation]);
    }


}
