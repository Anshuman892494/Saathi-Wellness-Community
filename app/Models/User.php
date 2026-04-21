<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model;

/**
 * User Model — stored in MongoDB 'users' collection.
 * Extends MongoDB Model and implements Laravel's Authenticatable contract.
 */
class User extends Model implements AuthenticatableContract
{
    use Authenticatable, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    /**
     * Mass-assignable fields.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',       // optional profile picture path
        'bio',          // short biography
        'bookmarks',    // array of post IDs the user has bookmarked
    ];

    /**
     * Hidden from serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast types.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'bookmarks'         => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Posts authored by this user. */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /** Comments made by this user. */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }
}
