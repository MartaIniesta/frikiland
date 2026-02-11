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
        if (!Auth::check()) {
            return redirect()->route('login');
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

    public function formatContent($content)
    {
        $content = e($content);

        $content = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            $content
        );

        $content = preg_replace_callback('/#(\w+)/', function ($matches) {
            $tag = strtolower($matches[1]);

            return '<a href="' . route('hashtag.show', $tag) . '" class="hashtag">#' . $tag . '</a>';
        }, $content);

        return $content;
    }

    public function render()
    {
        return view('livewire.posts.posts-following');
    }
}
