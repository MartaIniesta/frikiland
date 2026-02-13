<?php

namespace App\Livewire\Admin\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ContentRemovedNotification;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $confirmingDelete = null;

    public $editingPostId = null;
    public $editingContent = '';
    public array $editingMedia = [];
    public $confirmingImageDelete = false;
    public $mediaToDeleteIndex = null;

    protected $updatesQueryString = ['search'];

    protected $rules = [
        'editingContent' => 'required|string|min:3',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deletePost()
    {
        $post = Post::findOrFail($this->confirmingDelete);

        $owner = $post->user;

        if ($owner) {
            $excerpt = Str::limit($post->content, 80);

            $owner->notify(
                new ContentRemovedNotification('post', $excerpt)
            );
        }

        $post->delete();

        $this->confirmingDelete = null;
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        $this->editingPostId = $post->id;
        $this->editingContent = $post->content;
        $this->editingMedia = $post->media ?? [];
    }

    public function confirmRemoveMedia($index)
    {
        $this->mediaToDeleteIndex = $index;
        $this->confirmingImageDelete = true;
    }

    public function removeMedia()
    {
        if (isset($this->editingMedia[$this->mediaToDeleteIndex])) {

            $path = $this->editingMedia[$this->mediaToDeleteIndex];

            // borrar archivo físico
            Storage::disk('public')->delete($path);

            // quitar del array
            unset($this->editingMedia[$this->mediaToDeleteIndex]);
            $this->editingMedia = array_values($this->editingMedia);
        }

        $this->confirmingImageDelete = false;
        $this->mediaToDeleteIndex = null;
    }

    public function updatePost()
    {
        $this->validate();

        $post = Post::findOrFail($this->editingPostId);

        $post->update([
            'content' => $this->editingContent,
            'media'   => $this->editingMedia,
        ]);

        $this->reset([
            'editingPostId',
            'editingContent',
            'editingMedia',
        ]);
    }

    public function render()
    {
        $posts = Post::with('user', 'comments')
            ->where('content', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(15);

        return view('livewire.admin.posts.index', compact('posts'));
    }
}
