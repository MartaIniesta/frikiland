<?php

namespace App\Jobs;

use App\Models\Post;
use App\Mail\NewPostFromFollowedUserMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyFollowersNewPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle(): void
    {
        $author = $this->post->user;

        if (!$author) {
            return;
        }

        $author->followers()
            ->select('users.id', 'users.email')
            ->chunk(100, function ($followers) {

                foreach ($followers as $follower) {

                    Mail::to($follower->email)
                        ->queue(new NewPostFromFollowedUserMail($this->post));
                }
            });
    }
}
