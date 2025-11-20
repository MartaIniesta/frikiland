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

    // Para post principal 
    public $content; 
    public $media = []; 

    // Para replies 
    public $replyingToId; 
    public $replyContent; 
    public $replyMedia = []; 

    // Para edición 
    public $editingPostId; 
    public $editingContent; 
    public $existingMedia = []; // media existente 
    public $newMedia = []; // media nueva

    public function addPost()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'content' => 'required|min:1|max:280',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240', 
        ]);

        $mediaPaths = [];
        foreach ($this->media as $file) {
            $mediaPaths[] = $file->store('posts', 'public'); 
        }

        Post::create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'parent_id' => null,
            'media' => $mediaPaths ?: null,
        ]);

        $this->reset(['content', 'media']);
    }

    public function replyTo($postId)
    {
        $this->replyingToId = $postId;
    }

    public function addReply()
    {
        if (!Auth::check()) return redirect()->route('login');

        $this->validate([
            'replyContent' => 'required|min:1|max:280',
            'replyMedia.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240',
        ]);

        $mediaPaths = [];
        foreach ($this->replyMedia as $file) {
            $mediaPaths[] = $file->store('posts', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'content' => $this->replyContent,
            'parent_id' => $this->replyingToId,
            'media' => $mediaPaths ?: null,
        ]);

        Post::where('id', $this->replyingToId)->increment('comments_count');

        $this->reset(['replyingToId', 'replyContent', 'replyMedia']);
    }


    public function edit(Post $post)
    {
        $this->editingPostId = $post->id;
        $this->editingContent = $post->content;
        $this->existingMedia = $post->media ?? [];
        $this->newMedia = [];
    }


    public function update()
    {
        $this->validate([
            'editingContent' => 'required|min:1|max:280',
            'newMedia.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240',
        ]);

        $post = Post::findOrFail($this->editingPostId);

        $mediaPaths = $this->existingMedia;

        // Guardar media nueva
        foreach ($this->newMedia as $file) {
            $mediaPaths[] = $file->store('posts', 'public');
        }

        $post->update([
            'content' => $this->editingContent,
            'media' => $mediaPaths ?: null,
        ]);

        $this->reset(['editingPostId', 'editingContent', 'existingMedia', 'newMedia']);
    }


    public function delete(Post $post)
    {
        // Eliminar media asociada
        if ($post->media) {
            foreach ($post->media as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        // Reducir contador de comentarios si es una respuesta
        if ($post->parent_id) {
            Post::where('id', $post->parent_id)->decrement('comments_count');
        }

        $post->delete();
    }

    // Cancelar respuesta
    public function cancelReply()
    {
        $this->replyingToId = null;
        $this->replyContent = '';
        $this->replyMedia = [];
    }

    // Cancelar edición 
    public function cancelEdit() { 
        $this->editingPostId = null; 
        $this->editingContent = ''; 
        $this->existingMedia = []; 
        $this->newMedia = []; 
    }

    // Eliminar media individual al editar 
    public function removeMedia($postId, $index) { 
        $post = Post::find($postId); 
        $media = $post->media ?? []; 
        if (isset($media[$index])) { 
            Storage::disk('public')->delete($media[$index]); 
            array_splice($media, $index, 1); 
            $post->update(['media' => $media ?: null]); 
            if ($this->editingPostId === $postId) {
                $this->existingMedia = $media; 
            } 
        } 
    }
    
    public function render()
    {
        $posts = Post::whereNull('parent_id')
            ->with('replies.user')
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.posts', ['posts' => $posts]);
    }
}
