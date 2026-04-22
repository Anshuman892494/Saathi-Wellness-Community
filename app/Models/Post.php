<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Post Model — stored in MongoDB 'posts' collection.
 */
class Post extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'posts';

    /**
     * Mass-assignable fields.
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',   // e.g. general, fitness, mental-health, nutrition
        'image',      // optional Cloudinary secure URL
        'tags',       // array of tags
        'likes',      // array of user IDs who liked this post
        'views',      // integer view count
    ];

    protected $casts = [
        'views' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** User who authored the post. */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Comments on this post. */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    // ─── Accessors / Helpers ─────────────────────────────────────────────────

    public function getLikesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    public function getTagsAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    /** Returns the total number of likes. */
    public function getLikesCountAttribute(): int
    {
        return count($this->likes);
    }

    /** Check if a given user has already liked this post. */
    public function isLikedBy(string $userId): bool
    {
        return in_array($userId, $this->likes);
    }
}
