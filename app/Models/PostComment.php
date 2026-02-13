<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PostComment extends Model
{
    use HasFactory;

    protected $casts = [
        'media' => 'array',
    ];

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
        'media',
    ];


    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(PostComment::class, 'parent_id')->latest();
    }

    public function hashtags()
    {
        return $this->morphToMany(Hashtag::class, 'hashtagable');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }


    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavoritedBy(User $user): bool
    {
        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function shares()
    {
        return $this->morphMany(Share::class, 'shareable');
    }

    public function isSharedBy(User $user): bool
    {
        return $this->shares()->where('user_id', $user->id)->exists();
    }
}
