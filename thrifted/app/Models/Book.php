<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory, Searchable;
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'author',
        'edition',
        'price',
        'isbn',
        'cover',
        'condition',
        'new',
        'original',
        'format',
        'old_price',
        'available',
    ];
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'book_tags', 'book_id', 'tag_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function pictures()
    {
        return $this->hasMany(BookPicture::class);
    }
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'edition' => $this->edition,
            'description' => $this->description,
        ];
    }
    public function makeSearchableUsing(Collection $models): Collection
    {
        return $models->load('user');
    }
    public function searchableUsing(): Engine
    {
        return app(EngineManager::class)->engine('algolia');
    }
    public function wishlist()
    {
        return $this->hasMany(WishList::class);
    }

}
