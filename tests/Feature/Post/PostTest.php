<?php

use App\Models\Post;
use App\Models\User;

test('a post can be created', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    expect($post)->toBeInstanceOf(Post::class)
                 ->and($post->user_id)->toBe($user->id);
});

test('post media can be decoded as array', function () {
    $post = Post::factory()->create([
        'media' => json_encode(['https://example.com/image1.jpg'])
    ]);

    $media = json_decode($post->media, true);
    expect($media)->toBeArray()
                  ->toHaveCount(1)
                  ->and($media[0])->toBe('https://example.com/image1.jpg');
});
