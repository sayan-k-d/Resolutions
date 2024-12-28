<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
        'resolution_id',
        'reply_id',
        'user_name',
        'reply',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolution(): BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function childReplies(): BelongsTo
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($reply) {
    //         $reply->childReplies()->delete();
    //     });
    // }

}
