<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function create(User $user): bool
    {
        return $user->can('post.create');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->can('post.update')
            && $post->user_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->can('post.delete')
            && $post->user_id === $user->id;
    }
}
