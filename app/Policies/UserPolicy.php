<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function follow(User $authUser, User $targetUser): bool
    {
        return $authUser->id !== $targetUser->id;
    }

    public function unfollow(User $authUser, User $targetUser): bool
    {
        return $authUser->id !== $targetUser->id;
    }
}
