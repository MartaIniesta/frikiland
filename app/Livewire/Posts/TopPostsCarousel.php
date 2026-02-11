<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\Post;

class TopPostsCarousel extends Component
{
    public function render()
    {
        $posts = Post::withCount('favorites')
            ->with('user')
            ->orderByDesc('favorites_count')
            ->take(10)
            ->get();

        return view('livewire.posts.top-posts-carousel', [
            'posts' => $posts
        ]);
    }
}
