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
        $cards = Card::with(['books'])->where('user_id',$user_id)->get();
        return Inertia::render('Cards',[
            'cards' => $cards
        ]);
    }
    public function show($id)
    {
        $cards = Card::with(['books'])->where('user_id',Auth::user()->id)->get();
        $books_ids = Card::find($id)->books()->pluck('book_id')->toArray();
        $books = Book::with([
            'category',
            'tags',
            'user',
        ])->whereIn('id',$books_ids)->get();
        return Inertia::render('Card',[
            'books' => $books,
            'cards' => $cards
        ]);
    }
    public function create(Request $request)
    {
        \Log::info('name :'. $request->input('name'));
        $name = $request->input('name');
        $user_id = $request->input('user_id');
        $card = Card::create([
            'name' => $name,
            'user_id' => $user_id
        ]);
        return response()->json($card);
    }
    public function delete(Request $request)
    {

    }
    public function add_book(Request $request)
    {
        $book_id = $request->input('book_id');
        $card_id = $request->input('card_id');
        if (
            CardBook::create([
                'book_id' => $book_id,
                'card_id'=> $card_id
            ])
        ) {
            return response()->json('success');
        }
        else {
            return response()->json('failure');
        }
    }
    public function delete_book(Request $request)
    {

    }
    public function request_all(Request $request)
    {

    }

}
