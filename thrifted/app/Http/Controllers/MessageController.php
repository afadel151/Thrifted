<?php

namespace App\Http\Controllers;

use App\Events\MessageNotification;
use App\Events\MessageSend;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {

    }
    public function show($id)
    {


    }
    public function create()
    {

    }
    public function store(Request $request)
    {

        $message = Message::create([
            'creator' => $request->input('creator'),
            'message' => $request->input('message'),
            'chat_id'=> $request->input('chat_id')
        ]);
        if ($request->input('creator') == true) {
            $receiver_id = Chat::find($request->input('chat_id'))->target_id;
            $sender_id = Chat::find($request->input('chat_id'))->creator_id;
        }else {
            $receiver_id = Chat::find($request->input('chat_id'))->creator_id;
            $sender_id = Chat::find($request->input('chat_id'))->target_id;
        }
        $receiver = User::find($receiver_id);
        $sender = User::find($sender_id);
        MessageSend::dispatch($message);
        MessageNotification::dispatch($message,$sender,$receiver);
        return response()->json($message);
    }
    public function destroy($id)
    {

    }
}
