<?php

namespace App\Policies;

use App\Models\PostComment;
use App\Models\User;

class PostCommentPolicy
{
    public function update(User $user, PostComment $comment): bool
    {
        return $user->can('post.update')
            && $comment->user_id === $user->id;
    }

    public function delete(User $user, PostComment $comment): bool
    {
        return $user->can('post.delete')
            && $comment->user_id === $user->id;
    }
}
