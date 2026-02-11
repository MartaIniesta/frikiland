<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Post;
use App\Traits\HandlesPostMedia;
use App\Events\PostCreated;
use App\Models\Hashtag;

class PostsForYou extends Component
{
    use WithFileUploads, HandlesPostMedia, AuthorizesRequests;

    /** Feed */
    public Collection $posts;
    public int $perPage = 5;
    public int $loaded = 0;
    public bool $hasMore = true;

    /** Crear */
    public string $content = '';
    public array $media = [];
    public array $newMedia = [];

    /** Editar */
    public ?Post $editingPost = null;
    public string $editContent = '';
    public array $editMedia = [];
    public array $newEditMedia = [];

    /** Eliminar */
    public ?Post $deletingPost = null;

    public function mount()
    {
        $this->posts = collect();
        $this->loadPosts();
    }

    /* ---------------- FEED ---------------- */
    public function loadPosts()
    {
        if (! $this->hasMore) return;

        $newPosts = Post::with('user')
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

    /* ---------------- CREAR ---------------- */
    public function updatedNewMedia()
    {
        $this->handleMediaUpload($this->media, $this->newMedia, 'media');
    }

    public function removeTempMedia($index)
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media);
    }

    public function addPost()
    {
        $this->validate([
            'content' => 'required|min:1|max:280',
            'media.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'media'   => $this->storeMedia($this->media),
        ]);

        preg_match_all('/#(\w+)/', $this->content, $matches);

        foreach ($matches[1] as $tag) {
            $hashtag = Hashtag::firstOrCreate([
                'name' => strtolower($tag)
            ]);

            $post->hashtags()->syncWithoutDetaching([$hashtag->id]);
        }

        event(new PostCreated($post));

        $this->posts->prepend($post->load('user'));

        $this->reset(['content', 'media', 'newMedia']);
    }

    /* ---------------- EDITAR ---------------- */
    public function edit(int $postId)
    {
        $post = Post::findOrFail($postId);
        $this->authorize('update', $post);

        $this->editingPost = $post;
        $this->editContent = $post->content;
        $this->editMedia   = $post->media ?? [];
    }

    public function updatedNewEditMedia()
    {
        $this->handleMediaUpload($this->media, $this->newMedia, 'media');

        $this->newMedia = [];
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
            $mediaPaths[] = $item instanceof TemporaryUploadedFile
                ? $item->store('media', 'public')
                : $item;
        }

        $this->editingPost->update([
            'content' => $this->editContent,
            'media' => $mediaPaths,
        ]);

        $this->posts = $this->posts->map(function ($post) {
            if ($post->id === $this->editingPost->id) {
                $post->content = $this->editContent;
                $post->media = $this->editingPost->media;
            }
            return $post;
        });

        $this->reset([
            'editingPost',
            'editContent',
            'editMedia',
            'newEditMedia',
        ]);
    }

    public function cancelEdit()
    {
        $this->reset(['editingPost', 'editContent', 'editMedia', 'newEditMedia']);
    }

    /* ---------------- ELIMINAR ---------------- */
    public function confirmDelete(int $postId)
    {
        $post = Post::findOrFail($postId);
        $this->authorize('delete', $post);

        $this->deletingPost = $post;
    }

    public function deletePost()
    {
        $this->authorize('delete', $this->deletingPost);

        $id = $this->deletingPost->id;
        $this->deletingPost->delete();

        $this->posts = $this->posts->reject(fn($p) => $p->id === $id);
        $this->reset('deletingPost');
    }

    public function cancelDelete()
    {
        $this->reset('deletingPost');
    }

    public function render()
    {
        return view('livewire.posts.posts-for-you');
    }
}
