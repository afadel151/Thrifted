<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Card;
use App\Models\CardBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CardController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $cards = Card::with('book')->where('user_id',$user_id)->get();
        return response()->json($cards);
    }
    public function show($id)
    {
        $cards = Card::with('book')->where('user_id',Auth::user()->id)->get();
        return Inertia::render('Card',[
            'cards' => $cards
        ]);
    }
   
  
    public function add_book(Request $request)
    {
        $book_id = $request->input('book_id');
        $card = Card::create([
            'book_id' => $book_id,
            'user_id'=>  Auth::user()->id
        ]);
        $card->load('book');
        return response()->json($card);
        
    }
    public function delete_book(Request $request)
    {
        $book_id = $request->input('book_id');
        if (Card::where('book_id',$book_id)->where('user_id',Auth::user()->id)->delete()) {
            return response(status: 201);
        }else {
            return response(status:203);
        }
    }
    public function request_all(Request $request)
    {

    }

}
