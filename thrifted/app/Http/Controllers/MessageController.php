<?php

namespace App\Http\Controllers;

use App\Models\Message;
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
        return response()->json($message);
    }
    public function destroy($id)
    {

    }
}
