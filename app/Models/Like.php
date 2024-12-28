<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'resolution_id',
        'status', // 0: dislike, 1: like
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolution(): BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }
}
