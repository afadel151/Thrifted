<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookPicture;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BookController extends Controller
{
    public function add_picture(Request $request)
    {
        $book = Book::find($request->input('book_id'));
        if ($book) {
            if ($book->user_id != auth()->user()->id) {
                abort(403);
            } else {
                if ($request->hasFile('file')) {
                    $request_file = $request->file('file');
                    $path = '/public/books/';
                    $NewPath = Storage::disk('local')->putFile($path, $request_file);
                    BookPicture::create([
                        'book_id' => $book->id,
                        'picture' => $NewPath
                    ]);
                }
            }
        }
    }
    public function ist()
    {
        
    }
    public function edit($id)
    {
        $book = Book::with('category', 'pictures', 'tags')->find($id);
        $categories = Category::all();
        if ($book) {
            if ($book->user_id != auth()->user()->id) {
                abort(403);
            } else {
                return Inertia::render('BookEdit', [
                    'book' => $book,
                    'categories' => $categories
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
            DB::table('book_tags')->insert(
                [
                    'book_id' => $book->id,
                    'tag_id' => $tag,
                ]
            );
        }
        return response()->json($book);


    }
    public function search(Request $request)
    {
        $search = '%'. $request->input('search') .'%';
        $books = Book::where(function ($query) use ($search){
            $query->where('title', 'like',$search)
                    ->orWhere('author', 'like',$search)
                    ->orWhere('edition', 'like',$search);
        })->get();
        $books->load(['user','tags','category']);
        return response()->json($books);
    }
    public function show($id)
    {
        $book = Book::with('category', 'tags', 'user', 'pictures')->find($id);
        $tagsIds = $book->tags->pluck('id')->toArray();
        $relatedcategory = Book::where('books.id', '!=', $book->id)
            ->where('category_id', $book->category_id)
            ->pluck('id')
            ->toArray();
        $relatedtags = DB::table('book_tags')->select('book_id')
            ->whereIn('tag_id', $tagsIds)
            ->where('book_id', '!=', $book->id);
        $relatedbooks = Book::whereIn('id', $relatedcategory)
            ->orWhereIn('id', $relatedtags)
            ->take(10)
            ->get();
        return Inertia::render('BookShow', [
            'related_books' => $relatedbooks,
            'book' => $book,
        ]);
    }
    public function update(Request $request)
    {

        $book = Book::find($request->input('book_id'));
        if ($book && $book->user_id == Auth::user()->id) {
            if ($request->input('price') != $book->price) {
                $book->old_price = $book->price;
                $book->price = $request->input('price');
            }
            $book->update([
                'title' => $request->input('title'),
                'author' => $request->input('author'),
                'edition' => $request->input('edition'),
                'category_id' => $request->input('category_id'),
                'condition' => $request->input('condition'),
                'isbn' => $request->input('isbn'),
                'description' => $request->input('description'),
                'format' => $request->input('format'),
                'new' => $request->input('state') === 'New' ? true : false,
                'original' => $request->input('original') === 'Original' ? true : false,
            ]);
            return response()->json($book);
        }
    }
    public function chat_with_seller($id)
    {
        $seller_id = Book::find($id)->user_id;
        $user_id = Auth::user()->id;
        $chat = Chat::where(function ($query) use ($seller_id,$user_id){
            $query->where('creator_id', $user_id)
                    ->where('target_id',$seller_id);
        })->orWhere(function ($query) use ($seller_id, $user_id){
                $query->where('creator_id', $seller_id)
                    ->where('target_id',$user_id);
        })->first();
        if ($chat) {
            return redirect()->route('chats.show',$chat->id);
        }else {
            $chat = Chat::create([
                'creator_id' => $user_id,
                'target_id' => $seller_id
            ]);
            Message::create([
                'chat_id'=> $chat->id,
                'creator'=>true,
                'message'=>'',
                'book_id'=>$id
            ]);
            return redirect()->route('chats.show',$chat->id);
        }
    }

}
