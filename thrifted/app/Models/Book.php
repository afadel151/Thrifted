<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'book_tags', 'book_id', 'tag_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
