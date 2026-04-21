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
        'tags',       // array of tags
        'likes',      // array of user IDs who liked this post
        'views',      // integer view count
    ];

    protected $casts = [
        'tags'  => 'array',
        'likes' => 'array',
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

    /** Returns the total number of likes. */
    public function getLikesCountAttribute(): int
    {
        return count($this->likes ?? []);
    }

    /** Check if a given user has already liked this post. */
    public function isLikedBy(string $userId): bool
    {
        return in_array($userId, $this->likes ?? []);
    }
}
