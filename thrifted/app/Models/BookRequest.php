<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'wilaya_id',
        'status',
        'phone_number'
    ];
    protected $table = 'requests';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }
}
