<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Posts extends Component
{
    use WithFileUploads;

    public $content;
    public $media = [];

    public function addPost()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'content' => 'required|min:1|max:280',
            'media.*' => 'nullable|file|max:10240',
        ]);

        $mediaPaths = collect($this->media)
            ->map(fn ($file) => $file->store('posts', 'public'))
            ->toArray();

        Post::create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'media' => $mediaPaths ?: null,
        ]);

        $this->reset(['content', 'media']);
    }

    public function render()
    {
        return view('livewire.posts', [
            'posts' => Post::whereNull('parent_id')
                ->with(['user', 'replies'])
                ->latest()
                ->get(),
        ]);
    }
}
