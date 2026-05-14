<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * DailyStat Model — tracks user's daily health metrics (water, steps, etc).
 */
class DailyStat extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'daily_stats';

    protected $fillable = [
        'user_id',
        'date',        // e.g. YYYY-MM-DD
        'water_liters',
        'steps',
        'meditation_minutes',
        'sleep_hours',
    ];

    protected $casts = [
        'water_liters' => 'float',
        'steps' => 'integer',
        'meditation_minutes' => 'integer',
        'sleep_hours' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
