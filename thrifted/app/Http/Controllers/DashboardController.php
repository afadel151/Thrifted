<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newbooks = Book::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $classics_id = Tag::where('name','Classics')->first()->id; 
        $classic_books_ids = DB::table('book_tags')->select('book_id')->where('tag_id',$classics_id)->get()->pluck('book_id')->toArray();
        $classics = Book::with('user')->whereIn('id',$classic_books_ids)->orderBy('created_at', 'desc')->take(10)->get();
        $literature_id = Category::where('name','Literature & Fiction')->first()->id;
        $classics_id = Tag::where('name','Classics')->first()->id; 
        $liteartures = Book::with('user')->where('category_id',$literature_id)->get();
        return Inertia::render('Dashboard', [
            'newbooks' => $newbooks,
            'classics' => $classics,
            'literature' => $liteartures
        ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
