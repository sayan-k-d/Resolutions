<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'status',
        'likes',
        'comments',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
