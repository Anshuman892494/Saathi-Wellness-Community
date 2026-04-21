<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Comment Model — stored in MongoDB 'comments' collection.
 */
class Comment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'comments';

    /**
     * Mass-assignable fields.
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /** Post this comment belongs to. */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /** User who wrote the comment. */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
