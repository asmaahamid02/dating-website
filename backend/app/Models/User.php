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

    public function BlockedUsers()
    {
        return $this->hasMany(BlockedUser::class, 'blocked_user_id');
    }

    public function BlockedUsersBy()
    {
        return $this->hasMany(BlockedUser::class, 'user_id');
    }

    public function favoritor()
    {
        return $this->belongsToMany(User::class, 'favorite_users', 'favorite_user_id', 'user_id')->withTimestamps();
    }

    public function favoriting()
    {
        return $this->belongsToMany(User::class, 'favorite_users', 'user_id', 'favorite_user_id')->withTimestamps();
    }
}