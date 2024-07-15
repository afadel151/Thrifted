<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable;
class User extends Authenticatable
{
    use HasFactory, Notifiable,Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string, string,string, integer, boolean,boolean >
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'adress',
        'phone',
        'delivery',
        'payment_on_hand',
        'picture',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'goodreads_url',
        'rating',
        'wilaya_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function cards(): HasMany {
        return $this->hasMany(Card::class);
    }
    public function socials(): HasMany {
        return $this->hasMany(Social::class);
    }
    public function books(){
        return $this->hasMany(Book::class);
    }
    public function created_chats()
    {
        return $this->hasMany(Chat::class,'creator_id');
    }
    public function targeted_chats()
    {   
        return $this->hasMany(Chat::class,'target_id');
    }
    public function ratings(): HasMany 
    {
        return $this->hasMany(Rating::class,'rated_user_id');
    }
    public function wishlist()
    {
        return $this->hasMany(WishList::class);
    }
    public function requests()
    {
        return $this->hasMany(BookRequest::class);
    }
    // Suggestions about futur read , recommondation based on previous reads purchases 
    // book's rating from reader or seller / owner 
    // table users_books 
    // reviews 
    // friends / same interests 
    // saved sellers 
    // saved books
    // conversations about reviews and opinions (friends)
    // books selled 
    // book readers tracking with reviews and comments 
    // suggestions 
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
    // public function makeSearchableUsing(Collection $models): Collection
    // {
    //     return $models->load('wilaya');
    // }
    public function searchableUsing(): Engine
    {
        return app(EngineManager::class)->engine('algolia');
    }
   
    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }
}
