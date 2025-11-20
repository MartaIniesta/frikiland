<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'media' => 'array',
    ];

    protected $fillable = ['user_id', 'parent_id', 'content', 'media', 'likes_count', 'comments_count', 'shares_count',];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Post::class, 'parent_id');
    }
}
