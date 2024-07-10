<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get_unseen_messages(Request $request)
    {
        $user_id = Auth::user()->id;

        $chats = Chat::where(function ($query) use ($user_id){
            $query->where('creator_id',$user_id)
                ->orWhere('target_id',$user_id);
        })->get();
        $TotalUnseen = 0;
        foreach ($chats as $chat) {
            $chat_id = $chat->id;
            if ($chat->creator_id == $user_id) {
                $UnseenMessages = Message::where(function ($query) use ($chat_id) {
                    $query->where('chat_id', $chat_id)
                        ->where('creator', false)
                        ->where('seen', false);
                })->count();
            } else {
                $UnseenMessages = Message::where(function ($query) use ($chat_id) {
                    $query->where('chat_id', $chat_id)
                        ->where('creator', true)
                        ->where('seen', false);
                })->count();
            }
            if ($UnseenMessages > 0) {
                $TotalUnseen = $TotalUnseen + 1;
            }
          
        }
        return response()->json($TotalUnseen);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function carousel_details($id)
    {
        $user = User::find($id);
        $books = $user->books;
        $details = new \stdClass;
        $details->books = $books->count();
        $details->sold = $books->where('available',0)->count();
        $details->ratings = Rating::where('rated_user_id',$id)->count();
        return response()->json($details);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
