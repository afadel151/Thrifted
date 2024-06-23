<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'chat_id',
        'creator',
        'message',
        'seen',
        'book_id'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class,'book_id');
    }
}
