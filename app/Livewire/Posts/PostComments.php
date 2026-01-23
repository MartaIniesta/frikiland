<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\PostComment;
use App\Models\Post;
use App\Traits\HandlesPostMedia;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PostComments extends Component
{
    use WithFileUploads, HandlesPostMedia, AuthorizesRequests;

    public int $postId;

    // Crear comentario
    public string $content = '';
    public array $media = [];
    public array $newMedia = [];

    // Editar comentario
    public ?int $editingCommentId = null;
    public string $editingContent = '';
    public array $editingMedia = [];
    public array $newEditingMedia = [];

    // Responder
    public ?int $replyingToId = null;
    public string $replyContent = '';
    public array $replyMedia = [];
    public array $newReplyMedia = [];

    // Paginación
    public int $commentsToShow = 4;
    public array $repliesToShow = [];

    public string $resetKey;


    public function mount(int $postId)
    {
        $this->postId = $postId;
        $this->resetKey = uniqid();
    }


    public function updatedNewMedia()
    {
        $this->handleMediaUpload($this->media, $this->newMedia, 'media');
    }

    public function updatedNewReplyMedia()
    {
        $this->handleMediaUpload($this->replyMedia, $this->newReplyMedia, 'replyMedia');
    }

    public function updatedNewEditingMedia()
    {
        $this->handleMediaUpload($this->editingMedia, $this->newEditingMedia, 'editingMedia');
    }

    public function removeMedia($index)
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media);
    }

    public function removeReplyMedia($index)
    {
        unset($this->replyMedia[$index]);
        $this->replyMedia = array_values($this->replyMedia);
    }

    public function removeEditingMedia($index)
    {
        unset($this->editingMedia[$index]);
        $this->editingMedia = array_values($this->editingMedia);
    }


    public function addComment()
    {
        $this->validateContent('content');

        PostComment::create([
            'post_id' => $this->postId,
            'user_id' => Auth::id(),
            'content' => $this->content,
            'media'   => $this->storeMedia($this->media),
        ]);

        Post::where('id', $this->postId)->increment('comments_count');

        $this->resetForm();
    }


    public function toggleReply(int $commentId)
    {
        if ($this->replyingToId === $commentId) {
            $this->replyingToId = null;
            $this->replyContent = '';
            $this->replyMedia = [];
        } else {
            $this->replyingToId = $commentId;
            $this->replyContent = '';
            $this->replyMedia = [];
        }
    }

    public function addReply()
    {
        $this->validateContent('replyContent');

        PostComment::create([
            'post_id'   => $this->postId,
            'user_id'   => Auth::id(),
            'parent_id' => $this->replyingToId,
            'content'   => $this->replyContent,
            'media'     => $this->storeMedia($this->replyMedia),
        ]);

        Post::where('id', $this->postId)->increment('comments_count');

        $this->repliesToShow[$this->replyingToId] = 4;

        $this->resetForm();
    }


    public function edit(PostComment $comment)
    {
        $this->authorize('update', $comment);

        $this->editingCommentId = $comment->id;
        $this->editingContent = $comment->content;
        $this->editingMedia = $comment->media ?? [];
    }

    public function update()
    {
        $this->validateContent('editingContent');

        $comment = PostComment::findOrFail($this->editingCommentId);
        $this->authorize('update', $comment);

        $mediaPaths = [];

        foreach ($this->editingMedia as $item) {
            $mediaPaths[] = $item instanceof TemporaryUploadedFile
                ? $item->store('posts', 'public')
                : $item;
        }

        $comment->update([
            'content' => $this->editingContent,
            'media'   => $mediaPaths ?: null,
        ]);

        $this->resetForm();
    }


    public function delete(PostComment $comment)
    {
        $this->authorize('delete', $comment);

        $count = PostComment::where('parent_id', $comment->id)->count() + 1;

        $comment->delete();

        Post::where('id', $this->postId)->decrement('comments_count', $count);
    }


    public function loadMoreComments()
    {
        $this->commentsToShow += 4;
    }

    public function loadMoreReplies(int $commentId)
    {
        $this->repliesToShow[$commentId] = ($this->repliesToShow[$commentId] ?? 0) + 4;
    }

    public function loadLessReplies(int $commentId)
    {
        if (!isset($this->repliesToShow[$commentId])) {
            return;
        }

        if ($this->repliesToShow[$commentId] > 4) {
            $this->repliesToShow[$commentId] -= 4;
        } else {
            unset($this->repliesToShow[$commentId]);
        }
    }


    private function validateContent(string $field)
    {
        $this->resetValidation();

        $this->validate([
            $field => ['required', 'min:2', 'max:300'],
        ]);
    }

    private function resetForm()
    {
        $this->content = '';
        $this->media = [];
        $this->replyContent = '';
        $this->replyMedia = [];
        $this->editingCommentId = null;
        $this->editingContent = '';
        $this->editingMedia = [];
        $this->replyingToId = null;
        $this->resetKey = uniqid();
    }


    public function render()
    {
        $comments = PostComment::where('post_id', $this->postId)
            ->whereNull('parent_id')
            ->with('user')
            ->latest()
            ->take($this->commentsToShow)
            ->get();

        $totalComments = PostComment::where('post_id', $this->postId)
            ->whereNull('parent_id')
            ->count();

        return view('livewire.posts.post-comments', [
            'comments' => $comments,
            'totalComments' => $totalComments,
        ]);
    }
}
