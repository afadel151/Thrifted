<?php

namespace App\Http\Controllers;

use App\Events\ChatSeen;
use App\Models\Book;
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
        $chats = Chat::with('creator', 'target', 'unseen_messages')->where('creator_id', $user->id)
            ->orWhere('target_id', $user->id)->get();
        // dd($chats);
        return Inertia::render('Chats', ['chats' => $chats]);
    }
    public function selling_chats()
    {
        $user = Auth::user();
        $chats = Chat::with('creator')->where('target_id', $user->id)->get();
        return Inertia::render('Chats', ['chats' => $chats]);

    }
    public function messages($id)
    {
        $messages = Message::with('chat','book')->where('chat_id', $id)->orderByDesc('created_at')->paginate(15);
        return response()->json($messages);
    }
    public function show($id)
    {
        $user = Auth::user();
        $chat = Chat::with('creator','target')->find($id);
        $chats = Chat::with('creator', 'target','unseen_messages')->where('creator_id', $user->id)->orWhere('target_id', $user->id)->get();
        $Creator = Chat::find($id)->creator_id == $user->id ? true : false;
        return Inertia::render('Chat', [ 'chats' => $chats, 'creator' => $Creator, 'chat' => $chat]);

    }
    public function destroy($id)
    {

    }
    public function get_unseen_messages(Request $request)
    {
        $chat_id = $request->input('chat_id'); 
        $user_id = Auth::user()->id;
        $chat = Chat::find($chat_id);
        if ($chat->creator_id == $user_id) {
            $UnseenMessages = Message::where(function ($query) use ($chat_id) {
                $query->where('chat_id', $chat_id)
                    ->where('creator', false)
                    ->where('seen', false);
            })->get();
        } else {
            $UnseenMessages = Message::where(function ($query) use ($chat_id) {
                $query->where('chat_id', $chat_id)
                    ->where('creator', true)
                    ->where('seen', false);
            })->get();
        }
        return response()->json($UnseenMessages->count());
    }
    public function get_last_message(Request $request)
    {

        $chat_id = $request->input('chat_id'); // Use input() to fetch chat_id from the request
    
        if (!$chat_id) {
            return response()->json(['error' => 'Chat ID not provided'], 400);
        }
    
        $lastmessage = Message::with('book')->where('chat_id', $chat_id)->orderByDesc('created_at')->first();
    
        return response()->json($lastmessage);
    }
    public function buying_chats()
    {
        $user = Auth::user();
        $chats = Chat::with('target')->where('creator_id', $user->id)->get();
        return Inertia::render('Chats', ['chats' => $chats]);
    }
    public function mark_seen(Request $request)
    {
        try {
            $chat_id = $request->input('chat_id');
            $user_id = Auth::user()->id;
            $chat = Chat::find($chat_id);
            if ($chat->creator_id == $user_id) {
                $UnseenMessages = Message::where(function ($query) use ($chat_id) {
                    $query->where('chat_id', $chat_id)
                        ->where('creator', false)
                        ->where('seen', false);
                })->get();
            } else {
                $UnseenMessages = Message::where(function ($query) use ($chat_id) {
                    $query->where('chat_id', $chat_id)
                        ->where('creator', true)
                        ->where('seen', false);
                })->get();
            }

            foreach ($UnseenMessages as $message) {
                $message->update([
                    'seen' => true,
                ]);
            }
            ChatSeen::dispatch($chat_id);
            return response()->json('Success');
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
