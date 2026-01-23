<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\Post;

class PostsForYou extends Component
{
    public Collection $posts;
    public int $perPage = 5;
    public int $loaded = 0;
    public bool $hasMore = true;

    public function mount()
    {
        $this->posts = collect();
        $this->loadPosts();
    }

    public function loadPosts()
    {
        if (! $this->hasMore) {
            return;
        }

        $newPosts = Post::with('user')
            ->latest()
            ->skip($this->loaded)
            ->take($this->perPage)
            ->get();

        if ($newPosts->isEmpty()) {
            $this->hasMore = false;
            return;
        }

        $this->posts = $this->posts->merge($newPosts);
        $this->loaded += $newPosts->count();
    }

    public function render()
    {
        return view('livewire.posts.posts-for-you');
    }
}
