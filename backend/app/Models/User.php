<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'interested_in',
        'country',
        'city'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    //Get the users that favorited $this user.
    public function favoritor()
    {
        return $this->belongsToMany(User::class, 'favorite_users', 'favorite_user_id', 'user_id')->where('is_visible', 1)->withTimestamps();
    }

    //Get the users that are favorited by $this user.
    public function favoriting()
    {
        return $this->belongsToMany(User::class, 'favorite_users', 'user_id', 'favorite_user_id')->as('favorites')->where('is_visible', 1)->withTimestamps();
    }

    // Get the users that blocked $this user.
    public function blocker()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'blocked_user_id', 'user_id')->where('is_visible', 1)->withTimestamps();
    }

    //Get the users that are blocked by $this user.
    public function blocking()
    {
        return $this->belongsToMany(User::class, 'blocked_users', 'user_id', 'blocked_user_id')->as('blocks')->where('is_visible', 1)->withTimestamps();
    }

    public function sender()
    {
        return $this->belongsToMany(User::class, 'messages', 'receiver_id', 'sender_id')->where('is_visible', 1)->withTimestamps()->withPivot('message');
    }

    public function receiver()
    {
        return $this->belongsToMany(User::class, 'messages', 'sender_id', 'receiver_id')->where('is_visible', 1)->withTimestamps()->withPivot('message')->orderBy('messages.created_at', 'desc');
    }
}