<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\PostComment;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('username', ['david', 'marta', 'roberto'])->get();

        foreach ($users as $user) {

            $posts = Post::factory()->count(10)->create([
                'user_id' => $user->id,
            ]);

            foreach ($posts as $post) {

                $comments = PostComment::factory()->count(2)->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'parent_id' => null,
                ]);

                $post->update([
                    'comments_count' => 2
                ]);

                foreach ($comments as $comment) {

                    PostComment::factory()->count(rand(1, 3))->create([
                        'post_id' => $post->id,
                        'user_id' => $users->random()->id,
                        'parent_id' => $comment->id,
                    ]);
                }
            }
        }
    }
}
