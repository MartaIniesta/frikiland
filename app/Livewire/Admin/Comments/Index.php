<?php

namespace App\Livewire\Admin\Comments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PostComment;
use App\Notifications\ContentRemovedNotification;
use Illuminate\Support\Str;


class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $confirmingDelete = null;

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deleteComment()
    {
        $comment = PostComment::findOrFail($this->confirmingDelete);

        $owner = $comment->user;

        if ($owner) {

            $type = $comment->parent_id ? 'respuesta' : 'comentario';

            $excerpt = Str::limit($comment->content, 80);

            $owner->notify(
                new ContentRemovedNotification($type, $excerpt)
            );
        }

        $comment->delete();

        $this->confirmingDelete = null;
    }

    public function render()
    {
        $comments = PostComment::with(['user', 'post', 'replies'])
            ->where(function ($query) {
                $query->where('content', 'like', "%{$this->search}%")
                    ->orWhereHas('replies', function ($q) {
                        $q->where('content', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.comments.index', compact('comments'));
    }
}
