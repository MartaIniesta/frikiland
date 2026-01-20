<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use App\Models\Post;
use App\Traits\HandlesPostMedia;

class Posts extends Component
{
    use WithFileUploads, HandlesPostMedia, AuthorizesRequests;

    /** ─────────────
     *  CREAR POST
     *  ───────────── */
    public string $content = '';
    public array $media = [];
    public array $newMedia = [];

    /** ─────────────
     *  EDITAR POST
     *  ───────────── */
    public ?Post $editingPost = null;
    public string $editContent = '';
    public array $editMedia = [];
    public array $newEditMedia = [];

    /** ─────────────
     *  ELIMINAR POST
     *  ───────────── */
    public ?Post $deletingPost = null;

    /** ─────────────
     *  FEED
     *  ───────────── */
    public Collection $posts;
    public int $perPage = 5;
    public int $loaded = 0;
    public bool $hasMore = true;

    /* =============================
        INIT
    ============================== */
    public function mount()
    {
        $this->posts = collect();
        $this->loadPosts();
    }

    /* =============================
        LOAD POSTS
    ============================== */
    public function loadPosts()
    {
        if (!$this->hasMore) {
            return;
        }

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

        $this->posts = $this->posts->merge($newPosts);
        $this->loaded += $newPosts->count();
    }

    /* =============================
        MEDIA CREAR
    ============================== */
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

    /* =============================
        CREAR POST
    ============================== */
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

        $this->reset(['content', 'media', 'newMedia']);

        $this->refreshFeed();
    }

    /* =============================
        EDITAR POST
    ============================== */
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

        $this->refreshFeed();
    }

    /* =============================
        ELIMINAR POST
    ============================== */
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

        $this->refreshFeed();
    }

    /* =============================
        UTILIDADES
    ============================== */
    private function refreshFeed()
    {
        $this->posts = collect();
        $this->loaded = 0;
        $this->hasMore = true;

        $this->loadPosts();
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
