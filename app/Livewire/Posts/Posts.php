<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Traits\HandlesPostMedia;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class Posts extends Component
{
    use WithFileUploads, HandlesPostMedia;

    /** FORMULARIO */
    public string $content = '';
    public array $media = [];
    public array $newMedia = [];

    /** FEED */
    public array $posts = [];
    public int $perPage = 5;
    public int $loaded = 0;
    public bool $hasMore = true;

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        if (!$this->hasMore) return;

        $newPosts = Post::whereNull('parent_id')
            ->with(['user', 'replies'])
            ->latest()
            ->skip($this->loaded)
            ->take($this->perPage)
            ->get();

        if ($newPosts->isEmpty()) {
            $this->hasMore = false;
            return;
        }

        $this->posts = array_merge($this->posts, $newPosts->all());
        $this->loaded += $newPosts->count();
    }

    public function updatedNewMedia()
    {
        $this->handleMediaUpload(
            $this->media,
            $this->newMedia,
            'media'
        );
    }

    public function removeTempMedia($index)
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media);
    }

    public function addPost()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'content' => 'required|min:1|max:280',
            'media.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',
        ]);

        $mediaPaths = $this->storeMedia($this->media);

        Post::create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'media' => $mediaPaths,
        ]);

        // reset formulario
        $this->reset(['content', 'media', 'newMedia']);

        // refrescar feed
        $this->posts = [];
        $this->loaded = 0;
        $this->hasMore = true;
        $this->loadPosts();
    }

    public function render()
    {
        return view('livewire.posts.posts');
    }
}
