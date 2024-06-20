<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    }
    public function selling_chats()
    {
        $user = Auth::user();
        $chats = Chat::with('creator')->where('target_id', $user->id)->get();
        return Inertia::render('Chats',['chats' => $chats]);

    }
    public function create()
    {

    }
    public function show($id)
    {
        $messages = Message::with('chat')->where('chat_id',$id)->get();
        return Inertia::render('Chat',['messages' => $messages]);

    }
    public function destroy($id)
    {

    }
    public function buying_chats()
    {
        $user = Auth::user();
        $chats = Chat::with('target')->where('creator_id', $user->id)->get();
        return Inertia::render('Chats',['chats' => $chats]);
    }
}
