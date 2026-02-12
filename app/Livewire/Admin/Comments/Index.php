<?php

namespace App\Livewire\Admin\Comments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PostComment;

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
        PostComment::findOrFail($this->confirmingDelete)->delete();
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
