<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index() {

        $user = Auth::user();
        if(!$user) {
            return redirect('/login');
        }
        $conversations = $user->conversations;

        return view('home', [
            'user' => $user,
            'conversations' => $conversations
        ]);
    }
}
