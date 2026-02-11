<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\Hashtag;


class HashtagShow extends Component
{
    public $name;
    public $posts;
    public $comments;

    public function mount($name)
    {
        $this->name = strtolower($name);

        $hashtag = Hashtag::where('name', $this->name)->firstOrFail();

        $this->posts = $hashtag->posts()->with('user')->latest()->get();

        $this->comments = $hashtag->comments()->with('user')->latest()->get();
    }

    public function render()
    {
        return view('livewire.posts.hashtag-show');
    }
}
