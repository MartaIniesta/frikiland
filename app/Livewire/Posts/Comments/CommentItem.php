<?php

namespace App\Livewire\Posts\Comments;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Posts\PostComments;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentItem extends Component
{
    use WithFileUploads, AuthorizesRequests;

    protected $listeners = ['replyDeleted' => '$refresh'];

    public PostComment $comment;

    // Editar
    public bool $isEditing = false;
    public string $editingContent = '';
    public array $editingMedia = [];
    public array $newEditingMedia = [];

    // Reply
    public bool $isReplying = false;
    public string $replyContent = '';
    public array $replyMedia = [];
    public array $newReplyMedia = [];

    public int $repliesToShow = 0;

    public function mount(PostComment $comment)
    {
        $this->comment = $comment;
        $this->editingContent = $comment->content;
    }

    /* REPLY */
    public function toggleReply()
    {
        $this->isReplying = ! $this->isReplying;
    }

    public function updatedNewReplyMedia()
    {
        foreach ($this->newReplyMedia as $file) {
            $this->replyMedia[] = $file;
        }

        $this->newReplyMedia = [];
    }

    public function removeReplyMedia($index)
    {
        unset($this->replyMedia[$index]);
        $this->replyMedia = array_values($this->replyMedia);
    }

    public function addReply()
    {
        if (! Auth::check()) {
            return;
        }

        $this->validate([
            'replyContent' => 'required|string|max:1000',
        ]);

        $mediaPaths = [];

        foreach ($this->replyMedia as $item) {
            $mediaPaths[] = $item instanceof TemporaryUploadedFile
                ? $item->store('posts', 'public')
                : $item;
        }

        $this->comment->replies()->create([
            'post_id'   => $this->comment->post_id,
            'user_id'   => Auth::id(),
            'content'   => $this->replyContent,
            'media'     => $mediaPaths ?: null,
            'parent_id' => $this->comment->id,
        ]);

        $this->replyContent = '';
        $this->replyMedia = [];
        $this->isReplying = false;

        $this->comment->refresh();
    }

    /* EDIT */
    public function startEditing()
    {
        $this->authorize('update', $this->comment);

        $this->isEditing = true;
        $this->editingContent = $this->comment->content;
        $this->editingMedia = $this->comment->media ?? [];
    }

    public function updatedNewEditingMedia()
    {
        foreach ($this->newEditingMedia as $file) {
            $this->editingMedia[] = $file;
        }

        $this->newEditingMedia = [];
    }

    public function removeEditingMedia($index)
    {
        unset($this->editingMedia[$index]);
        $this->editingMedia = array_values($this->editingMedia);
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->editingContent = $this->comment->content;
    }

    public function update()
    {
        $this->authorize('update', $this->comment);

        $this->validate([
            'editingContent' => 'required|string|max:1000',
        ]);

        $mediaPaths = [];

        foreach ($this->editingMedia as $item) {
            $mediaPaths[] = $item instanceof TemporaryUploadedFile
                ? $item->store('posts', 'public')
                : $item;
        }

        $this->comment->update([
            'content' => $this->editingContent,
            'media'   => $mediaPaths ?: null,
        ]);

        $this->comment->refresh();

        $this->isEditing = false;
    }

    public function delete()
    {
        $this->authorize('delete', $this->comment);

        $this->comment->delete();

        $this->dispatch('commentDeleted')
            ->to(PostComments::class);

        $this->dispatch('replyDeleted')
            ->to(self::class);
    }

    public function loadMoreReplies()
    {
        $this->repliesToShow += 4;
    }

    public function loadLessReplies()
    {
        if ($this->repliesToShow > 4) {
            $this->repliesToShow -= 4;
        } else {
            $this->repliesToShow = 0;
        }
    }

    public function render()
    {
        return view('livewire.posts.comments.comment-item');
    }
}
