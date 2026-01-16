<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostShow extends Component
{
    use WithFileUploads;

    public Post $post;

    public $replyContent = '';
    public $replyMedia = [];

    public function addReply()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'replyContent' => 'required|min:1|max:280',
            'replyMedia.*' => 'nullable|file|max:10240',
        ]);

        $mediaPaths = collect($this->replyMedia)
            ->map(fn($file) => $file->store('posts', 'public'))
            ->toArray();

        Post::create([
            'user_id'   => Auth::id(),
            'content'   => $this->replyContent,
            'parent_id' => $this->post->id,
            'media'     => $mediaPaths ?: null,
        ]);

        $this->post->increment('comments_count');

        $this->reset(['replyContent', 'replyMedia']);
        $this->post->refresh();
    }

    public function render()
    {
        return view('livewire.post-show', [
            'replies' => $this->post
                ->replies()
                ->with('user')
                ->latest()
                ->get(),
        ]);
    }
}
