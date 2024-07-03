<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardBook extends Model
{
    use HasFactory;
    protected $table = 'card_books';
    protected $fillable = [
        'card_id',
        'book_id'
    ];
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
