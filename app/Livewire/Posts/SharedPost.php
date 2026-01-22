<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class SharedPost extends Component
{
    public Post $post;
    public bool $isShared = false;

    public function mount(Post $post)
    {
        $this->post = $post;

        if (Auth::check()) {
            $this->isShared = Auth::user()
                ->sharedPosts()
                ->where('post_id', $post->id)
                ->exists();
        }
    }

    public function toggleShare()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        Auth::user()->sharedPosts()->toggle($this->post->id);
        $this->isShared = ! $this->isShared;
    }

    public function render()
    {
        return view('livewire.posts.shared-post');
    }
}
