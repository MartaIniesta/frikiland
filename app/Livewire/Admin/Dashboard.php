<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Post;
use App\Models\User;
use App\Models\PostComment;

class Dashboard extends Component
{
    public $postsCount;
    public $usersCount;
    public $commentsCount;

    public function mount()
    {
        $this->postsCount = Post::count();
        $this->usersCount = User::count();
        $this->commentsCount = PostComment::count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
