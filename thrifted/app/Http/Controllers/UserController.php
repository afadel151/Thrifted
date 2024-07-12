<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRequest;
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
    public function untreated_requests($id)
    {
        $books = Book::where('user_id',$id)
                        ->pluck('id')
                        ->toArray();
        $untreated = BookRequest::where(function ($query) use ($books) {
            $query->whereIn('book_id',$books)
                    ->where('status','Untreated');
        })->count();
        return response()->json($untreated);
    }
    public function contact_user($id)
    {
        $user_id = Auth::user()->id;
        $chat = Chat::where(function ($query) use ( $id, $user_id) {
            $query->where('creator_id', $user_id)
                ->where('target_id', $id);
        })->orWhere(function ($query) use ($id, $user_id) {
            $query->where('creator_id', $id)
                ->where('target_id', $user_id);
        })->first();
        if ($chat) {
            return redirect()->route('chats.show', $chat->id);
        } else {
            $chat = Chat::create([
                'creator_id' => $user_id,
                'target_id' => $id
            ]);
            return redirect()->route('chats.show', $chat->id);
        }
    }
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
    public function chats(Request $request)
    {
        $chats = Chat::with(['creator','target'])->where(function ($query) use ($request) {
            $query->where('creator_id',$request->input('user_id'))
                ->orWhere('target_id',$request->input('user_id'));
        })->get();
        return response()->json($chats);
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
