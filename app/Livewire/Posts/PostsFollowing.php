<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostsFollowing extends Component
{
    public Collection $posts;
    public array $followingIds = [];
    public int $perPage = 5;
    public int $loaded = 0;
    public bool $hasMore = true;

    public function mount()
    {
        if (! Auth::check()) {
            redirect()->route('login')->send();
        }

        $this->followingIds = Auth::user()
            ->following()
            ->pluck('users.id')
            ->toArray();

        $this->posts = collect();
        $this->loadPosts();
    }

    public function loadPosts()
    {
        if (! $this->hasMore || empty($this->followingIds)) {
            $this->hasMore = false;
            return;
        }

        $newPosts = Post::with('user')
            ->whereIn('user_id', $this->followingIds)
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
        return view('livewire.posts.posts-following');
    }
}
