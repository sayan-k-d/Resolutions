<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'resolution_id',
        'comment',
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
