<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'favorite_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favoriteUser()
    {
        return $this->belongsTo(User::class, 'favorite_user_id');
    }
}