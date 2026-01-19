<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Traits\HandlesPostMedia;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostShow extends Component
{
    use WithFileUploads, HandlesPostMedia;

    public Post $post;

    /** FORMULARIO REPLY */
    public string $replyContent = '';
    public array $replyMedia = [];
    public array $newReplyMedia = [];
    public ?int $replyingTo = null;

    /** REPLIES DE PRIMER NIVEL (SCROLL INFINITO) */
    public array $replies = [];
    public int $perPage = 10;
    public int $loaded = 0;
    public bool $hasMoreReplies = true;

    /** REPLIES HIJAS */
    public int $repliesStep = 5;
    public array $visibleReplies = []; // [comment_id => visible_count]

    public function mount()
    {
        $this->loadReplies();
    }

    /**
     * Carga SOLO replies de primer nivel
     */
    public function loadReplies()
    {
        if (!$this->hasMoreReplies) return;

        $newReplies = $this->post
            ->replies()
            ->with('user') // ❗ NO cargar replies hijas
            ->latest()
            ->skip($this->loaded)
            ->take($this->perPage)
            ->get();

        if ($newReplies->isEmpty()) {
            $this->hasMoreReplies = false;
            return;
        }

        $this->replies = array_merge($this->replies, $newReplies->all());
        $this->loaded += $newReplies->count();
    }

    /** MEDIA */
    public function updatedNewReplyMedia()
    {
        $this->handleMediaUpload(
            $this->replyMedia,
            $this->newReplyMedia,
            'replyMedia'
        );
    }

    public function removeReplyMedia($index)
    {
        unset($this->replyMedia[$index]);
        $this->replyMedia = array_values($this->replyMedia);
    }

    /** RESPONDER */
    public function startReply(int $postId)
    {
        $this->replyingTo = $postId;
        $this->reset(['replyContent', 'replyMedia', 'newReplyMedia']);
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->reset(['replyContent', 'replyMedia', 'newReplyMedia']);
    }

    public function addReply()
    {
        if (!Auth::check() || !$this->replyingTo) return;

        $this->validate([
            'replyContent' => 'required|min:1|max:280',
            'replyMedia.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',
        ]);

        $mediaPaths = $this->storeMedia($this->replyMedia);

        Post::create([
            'user_id'   => Auth::id(),
            'content'   => $this->replyContent,
            'parent_id' => $this->replyingTo,
            'media'     => $mediaPaths,
        ]);

        $this->post->increment('comments_count');

        // refrescar replies de primer nivel
        $this->replies = [];
        $this->loaded = 0;
        $this->hasMoreReplies = true;
        $this->loadReplies();

        $this->cancelReply();
        $this->post->refresh();
    }

    /**
     * Replies hijas visibles (por defecto 0)
     */
    public function getVisibleReplies(Post $comment)
    {
        $limit = $this->visibleReplies[$comment->id] ?? 0;

        if ($limit === 0) {
            return collect();
        }

        return $comment->replies()
            ->with('user')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function showMoreReplies(int $commentId)
    {
        $this->visibleReplies[$commentId] =
            ($this->visibleReplies[$commentId] ?? 0) + $this->repliesStep;
    }

    public function showLessReplies(int $commentId)
    {
        $current = $this->visibleReplies[$commentId] ?? 0;

        $newValue = $current - $this->repliesStep;

        if ($newValue <= 0) {
            unset($this->visibleReplies[$commentId]);
        } else {
            $this->visibleReplies[$commentId] = $newValue;
        }
    }

    public function render()
    {
        return view('livewire.posts.post-show');
    }
}
