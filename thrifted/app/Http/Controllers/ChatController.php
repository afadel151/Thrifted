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
        $chats = Chat::with('creator','target')->where('creator_id', $user->id)
                                                ->orWhere('target_id',$user->id)->get();
        // dd($chats);
        return Inertia::render('Chats',['chats' => $chats]);
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
        $user = Auth::user();
        $messages = Message::with('chat')->where('chat_id',$id)->get();
        $chats = Chat::with('creator','target')->where('creator_id',$user->id)->orWhere('target_id',$user->id)->get();
        $Creator = Chat::find($id)->creator_id == $user->id ? true : false; 
        return Inertia::render('Chat',['messages' => $messages, 'chats' => $chats,'creator'=>$Creator]);

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
