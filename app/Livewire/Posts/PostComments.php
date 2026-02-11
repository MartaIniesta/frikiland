<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\PostComment;
use App\Models\Post;
use App\Traits\HandlesPostMedia;
use App\Traits\HandlesHashtags;
use App\Traits\NotifiesOnReply;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostComments extends Component
{
    use WithFileUploads, HandlesPostMedia, HandlesHashtags, NotifiesOnReply, AuthorizesRequests;

    protected $listeners = ['commentDeleted' => '$refresh'];

    protected $casts = ['media' => 'array',];

    public int $postId;

    public string $content = '';
    public array $media = [];
    public array $newMedia = [];

    public int $commentsToShow = 4;

    public function mount(int $postId)
    {
        $this->postId = $postId;
    }

    public function updatedNewMedia()
    {
        $this->handleMediaUpload($this->media, $this->newMedia, 'media');
    }

    public function removeMedia($index)
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media);
    }

    public function addComment()
    {
        $this->authorize('create', PostComment::class);

        $this->validate([
            'content' => ['required', 'min:2', 'max:300'],
        ]);

        $comment = PostComment::create([
            'post_id' => $this->postId,
            'user_id' => Auth::id(),
            'content' => $this->content,
            'media'   => $this->storeMedia($this->media),
        ]);

        $this->syncHashtags($comment, $this->content);

        Post::where('id', $this->postId)->increment('comments_count');

        $post = Post::find($this->postId);
        $this->notifyReply($post->user, $comment);

        $this->reset(['content', 'media', 'newMedia']);
    }

    public function loadMoreComments()
    {
        $this->commentsToShow += 4;
    }

    public function render()
    {
        $comments = PostComment::where('post_id', $this->postId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->take($this->commentsToShow)
            ->get();

        return view('livewire.posts.post-comments', compact('comments'));
    }
}
