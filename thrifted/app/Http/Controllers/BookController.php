<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BookController extends Controller
{
    public function edit($id)
    {
        $book = Book::find($id);
        if ($book) {
            if ($book->user_id != auth()->user()->id) {
                abort(403);
            } else {
                return Inertia::render('BookEdit', [
                    'book' => $book,
                ]);
            }
        }
    }
    public function my_books()
    {
        $books = auth()->user()->books;
        $tags = Tag::all();
        $user = auth()->user();
        $categories = Category::all();
        return Inertia::render('MyBoooks', [
            'books' => $books,
            'tags' => $tags,
            'user' => $user,
            'categories' => $categories
        ]);
    }
    public function create(Request $request)
    {
        $book = new Book;
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        if ($request->input('description') !== '') {
            $book->description = $request->input('description');
        }
        if ($request->hasFile('file')) {
            $request_file = $request->file('file');
            $path = '/public/books/';
            $NewPath = Storage::disk('local')->putFile($path, $request_file);
            $book->cover = $NewPath;
        }
        if ($request->input('isbn') != 0) {
            $book->isbn = $request->input('isbn');
        }
        $book->edition = $request->input('edition');
        $book->user_id = $request->input('user_id');
        $book->format = $request->input('format');
        $book->condition = $request->input('condition');
        $book->new = $request->input('state') === 'New' ? true : false;
        $book->category_id = $request->input('category_id');
        $book->price = $request->input('price');
        $book->original = $request->input('original') === 'Original' ? true : false;
        $book->save();
        $tags = $request->input('tags', []);
                foreach ($tags as $tag) {
            \Log::info('tags:'. $tag);
        }
        return response()->json($book);


    }

    public function show($id)
    {
        $book = Book::find($id);
        $relatedbooks = Book::where('category_id', $book->category_id)
                                ->where('id', '!=', $book->id)
                                ->take(10)->get();
        return Inertia::render('BookShow', [
            'related_books' => $relatedbooks,
            'book' => $book,
        ]);
    }

}
