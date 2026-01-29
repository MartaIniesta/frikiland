<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Mail\PostCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendPostCreatedEmail
{
    public function handle(PostCreated $event): void
    {
        $user = $event->post->user;

        if (! $user || ! $user->email) {
            return;
        }

        Mail::to($user->email)
            ->send(new PostCreatedMail($event->post));
    }
}
