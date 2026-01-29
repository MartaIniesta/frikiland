<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserFollowed extends Notification
{
    use Queueable;

    public function __construct(public User $follower) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'user_followed',
            'follower_id' => $this->follower->id,
        ];
    }
}
