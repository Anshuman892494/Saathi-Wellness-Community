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
        'profile_photo', // Cloudinary secure URL for profile picture
        'cover_photo',  // Cloudinary secure URL for cover photo
        'bio',          // short biography
        'bookmarks',    // array of post IDs the user has bookmarked
        'role',         // 'user' or 'admin'
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

    // ─── Accessors / Helpers ─────────────────────────────────────────────────

    public function getBookmarksAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    /** Check if user is administrator. */
    public function isAdmin(): bool
    {
        return ($this->role ?? 'user') === 'admin';
    }
}
