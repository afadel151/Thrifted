<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'creator_id',
        'target_id',
        'last_update'
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
