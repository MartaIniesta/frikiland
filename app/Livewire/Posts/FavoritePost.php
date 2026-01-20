<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class FavoritePost extends Component
{
    public Post $post;
    public bool $isFavorite = false;

    public function mount(Post $post)
    {
        $this->post = $post;

        if (Auth::check()) {
            $this->isFavorite = Auth::user()
                ->favoritePosts()
                ->where('post_id', $post->id)
                ->exists();
        }
    }

    public function toggleFavorite()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        Auth::user()->favoritePosts()->toggle($this->post->id);
        $this->isFavorite = ! $this->isFavorite;
    }

    public function render()
    {
        return view('livewire.posts.favorite-post');
    }
}
