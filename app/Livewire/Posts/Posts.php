<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Post;
use App\Traits\HandlesPostMedia;

class Posts extends Component
{
    use WithFileUploads, HandlesPostMedia, AuthorizesRequests;

    // Crear post
    public string $content = '';
    public array $media = [];
    public array $newMedia = [];

    // Editar post
    public ?Post $editingPost = null;
    public string $editContent = '';
    public array $editMedia = [];
    public array $newEditMedia = [];

    // Eliminar post
    public ?Post $deletingPost = null;


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
            redirect()->route('login')->send();
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

        $this->reset(['content', 'media', 'newMedia']);

        $this->dispatch('post-created');
    }

    public function edit(int $postId)
    {
        $post = Post::findOrFail($postId);
        $this->authorize('update', $post);

        $this->editingPost = $post;
        $this->editContent = $post->content;
        $this->editMedia = $post->media ?? [];
    }

    public function updatedNewEditMedia()
    {
        foreach ($this->newEditMedia as $file) {
            $this->editMedia[] = $file;
        }
    }

    public function removeEditMedia($index)
    {
        unset($this->editMedia[$index]);
        $this->editMedia = array_values($this->editMedia);
    }

    public function updatePost()
    {
        $this->authorize('update', $this->editingPost);

        $this->validate([
            'editContent' => 'required|min:1|max:280',
        ]);

        $mediaPaths = [];

        foreach ($this->editMedia as $item) {
            if ($item instanceof TemporaryUploadedFile) {
                $mediaPaths[] = $item->store('media', 'public');
            } else {
                $mediaPaths[] = $item;
            }
        }

        $this->editingPost->update([
            'content' => $this->editContent,
            'media' => $mediaPaths,
        ]);

        $this->reset([
            'editingPost',
            'editContent',
            'editMedia',
            'newEditMedia',
        ]);

        $this->dispatch('post-updated');
    }

    public function confirmDelete(int $postId)
    {
        $post = Post::findOrFail($postId);
        $this->authorize('delete', $post);

        $this->deletingPost = $post;
    }

    public function deletePost()
    {
        $this->authorize('delete', $this->deletingPost);

        $this->deletingPost->delete();
        $this->reset('deletingPost');

        $this->dispatch('post-deleted');
    }

    public function cancelEdit()
    {
        $this->reset([
            'editingPost',
            'editContent',
            'editMedia',
            'newEditMedia',
        ]);
    }

    public function cancelDelete()
    {
        $this->reset('deletingPost');
    }

    public function render()
    {
        return view('livewire.posts.posts');
    }
}
