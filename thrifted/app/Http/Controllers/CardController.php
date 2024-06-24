<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $cards = Card::with('books')->where('user_id',$user_id)->get();
    }
    public function show($id)
    {

    }
    public function create()
    {

    }
    public function delete(Request $request)
    {

    }
    public function add_book(Request $request)
    {

    }
    public function delete_book(Request $request)
    {

    }
    public function request_all(Request $request)
    {

    }

}
