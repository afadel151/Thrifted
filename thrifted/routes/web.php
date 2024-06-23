<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;
use App\Models\Book;
use App\Models\Tag;
use App\Models\User;
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
Route::prefix('/api')->group(function () {
    Route::prefix('books')->group(function () {
        Route::post('create', [BookController::class, 'create'])->name('books.create');
        Route::post('add_picture', [BookController::class, 'add_picture']);
        Route::post('update', [BookController::class, 'update']);
    });
    Route::prefix('messages')->group(function(){
        Route::post('/',[MessageController::class, 'store']);
    });
    Route::prefix('chats')->group(function(){
        Route::post('/mark_seen',[ChatController::class,'mark_seen']);
        Route::post('/get_last_message',[ChatController::class,'get_last_message']);
        Route::post('/get_unseen_messages',[ChatController::class,'get_unseen_messages']);
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
})->middleware(['auth', 'verified']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/verify_code',[ProfileController::class, 'verifyCode'])->name('profile.verify_code');
    Route::post('/profile/send_code',[ProfileController::class, 'sendVerificationCode'])->name('profile.send_verification_code');
    // send_verification_code
});
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialController::class, 'callback']);
require __DIR__ . '/auth.php';
