<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPicture extends Model
{
    use HasFactory;
    protected $table = 'book_pictures';
    protected $fillable = [
        'book_id',
        'picture'
    ];
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
