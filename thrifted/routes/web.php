<?php

use App\Http\Controllers\BookController;
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
Route::prefix('/api')->group(function (){
    Route::prefix('books')->group(function (){
        Route::post('create',[BookController::class, 'create'])->name('books.create');
    });
});
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('/books')->group(function () {
    Route::get('/my_books',[BookController::class,'my_books'])->middleware(['auth', 'verified'])->name('books.my_books');
    Route::get('/{id}',[BookController::class,'show'])->name('books.show');
    Route::get('/{id}/edit',[BookController::class,'edit'])->middleware(['auth','verified'])->name('books.edit');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialController::class, 'callback']);
require __DIR__ . '/auth.php';
