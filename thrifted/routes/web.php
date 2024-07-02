<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;
use App\Models\Book;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::get('/search', function () {
    // return Inertia::render('Search');
    // return Inertia::render('Search');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::prefix('/api')->group(function () {
    Route::prefix('books')->group(function () {
        Route::post('create', [BookController::class, 'create'])->name('books.create');
        Route::post('add_picture', [BookController::class, 'add_picture']);
        Route::post('update', [BookController::class, 'update']);
        Route::post('search', [BookController::class, 'search']);
    });
    Route::prefix('messages')->group(function(){
        Route::post('/',[MessageController::class, 'store']);
    });
    Route::prefix('chats')->group(function(){
        Route::get('/{id}/messages',[ChatController::class,'messages'])->name('chats.messages');
        Route::post('/mark_seen',[ChatController::class,'mark_seen']);
        Route::post('/get_last_message',[ChatController::class,'get_last_message']);
        Route::post('/get_unseen_messages',[ChatController::class,'get_unseen_messages']);
    });
    Route::prefix('users')->group(function(){
        Route::post('/unseen_messages',[UserController::class, 'get_unseen_messages']);
        Route::post('/update_picture',[ProfileController::class, 'UpdateProfilePicture']);
Route::post('/search',[ProfileController::class, 'search');
    });
    
});
Route::get('/dashboard', function () {
    $newbooks = Book::with('user')->orderBy('created_at', 'desc')->take(10)->get();
    return Inertia::render('Dashboard', [
        'newbooks' => $newbooks,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('chat', function () {
    return view('chat');
});
// 
Route::prefix('/cards')->group(function (){
    Route::get('/',[CardController::class,'index'])->name('cards.index');
    Route::get('/{id}',[CardController::class,'show'])->name('cards.show');
    Route::post('/create',[CardController::class,'create'])->name('cards.create');
    Route::post('/delete',[CardController::class,'delete'])->name('cards.delete');
    Route::post('/add_book',[CardController::class,'add_book'])->name('cards.add_book');
    Route::post('/delete_book',[CardController::class,'delete_book'])->name('cards.delete_book');
    Route::post('/request_all',[CardController::class,'request_all'])->name('cards.request_all');
})->middleware(['auth', 'verified']);
// 
Route::prefix('/chats')->group(function() {
    Route::get('/',[ChatController::class,'index'])->name('chats.index');
    Route::get('/sellings', [ChatController::class,'selling_chats'])->name('chats.selling');
    Route::get('/buying', [ChatController::class,'buying_chats'])->name('chats.buying');
    Route::get('/{id}', [ChatController::class,'show'])->name('chats.show');
    Route::post('/',[ChatController::class,'create'])->name('chats.create');
})->middleware(['auth', 'verified']);
Route::prefix('/books')->group(function () {
    Route::get('/my_books', [BookController::class, 'my_books'])->middleware(['auth', 'verified'])->name('books.my_books');
    Route::get('/{id}', [BookController::class, 'show'])->name('books.show');
    Route::get('/{id}/edit', [BookController::class, 'edit'])->middleware(['auth', 'verified'])->name('books.edit');
    Route::get('/{id}/chat_with_seller',[BookController::class,'chat_with_seller'])->name('books.chat_with_seller');
})->middleware(['auth', 'verified']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/verify_code',[ProfileController::class, 'verifyCode'])->name('profile.verify_code');
    Route::post('/profile/send_code',[ProfileController::class, 'sendVerificationCode'])->name('profile.send_verification_code');
    Route::get('/profile/{id}',[ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{id}/books',[ProfileController::class, 'books'])->name('profile.books');
    Route::get('/profile/{id}/books_pagination',[ProfileController::class, 'books_pagination'])->name('profile.books_pagination');
    
});

Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialController::class, 'callback']);
require __DIR__ . '/auth.php';
