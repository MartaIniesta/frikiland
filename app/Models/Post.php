<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $casts = [
        'media' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'content',
        'media',
        'likes_count',
        'comments_count',
        'shares_count',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
