<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    protected $fillable = ['name',];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'hashtagable');
    }

    public function comments()
    {
        return $this->morphedByMany(PostComment::class, 'hashtagable');
    }
}
