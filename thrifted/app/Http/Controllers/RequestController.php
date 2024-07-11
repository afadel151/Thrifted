<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RequestController extends Controller
{
    public function index()
    {
        $books = Book::where('user_id', Auth::user()->id)->pluck('id')->toArray();
        $requests = Request::with(['book','user', 'wilaya'])->whereIn('book_id',$books)->get();
        return Inertia::render('Requests',[
            'requests' => $requests
        ]);
    }

    public function make_requets(Request $request)
    {

    }
}
