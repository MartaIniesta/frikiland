<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostsFeed extends Component
{
    public $posts = [];
    public $perPage = 5; // cuantos posts cargar de inicio
    public $loaded = 0; // posts ya cargados

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $newPosts = Post::latest()
            ->skip($this->loaded)
            ->take($this->perPage)
            ->get();

        $this->posts = array_merge($this->posts, $newPosts->toArray());
        $this->loaded += $newPosts->count();
    }

    public function render()
    {
        return view('livewire.posts-feed');
    }
}
