<?php

namespace App\Traits;

use App\Notifications\ContentReplied;

trait NotifiesOnReply
{
    protected function notifyReply($targetUser, $model): void
    {
        if ($targetUser->id !== auth()->id()) {
            $targetUser->notify(
                new ContentReplied(auth()->user(), $model)
            );
        }
    }
}
