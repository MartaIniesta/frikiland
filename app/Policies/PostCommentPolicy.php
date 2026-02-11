<?php

namespace App\Policies;

use App\Models\PostComment;
use App\Models\User;

class PostCommentPolicy
{
    public function create(User $user): bool
    {
        return $user->can('comment.create');
    }

    public function update(User $user, PostComment $comment): bool
    {
        return $comment->user_id === $user->id
            && $user->can('comment.update');
    }

    public function delete(User $user, PostComment $comment): bool
    {
        return $comment->user_id === $user->id
            && $user->can('comment.delete');
    }
}
