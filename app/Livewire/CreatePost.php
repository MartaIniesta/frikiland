<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Str;

class CreatePost extends Component
{
    use WithFileUploads;

    public $content;
    public $media;

    public function submit()
    {
        $this->validate([
            'content' => 'required|string|max:280',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240',
        ]);

        $mediaPath = $this->media ? $this->media->store('posts', 'public') : null;

        Post::create([
            'user_id' => auth()->id(),
            'content' => $this->content,
            'media' => $mediaPath,
        ]);

        $this->reset(['content', 'media']); // Limpiar formulario
        session()->flash('message', 'Post creado correctamente.');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
