<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class ProfileUser extends Component
{
    public User $user;
    public string $tab = 'posts';

    protected $queryString = [
        'tab' => ['except' => 'posts'],
    ];

    public function mount($username)
    {
        $this->user = User::where('username', $username)
            ->withCount('followers')
            ->firstOrFail();
    }

    public function setTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function getPostsProperty()
    {
        if (
            $this->tab === 'favorites' &&
            ! $this->user->canViewFavorites(Auth::user())
        ) {
            return collect();
        }

        if ($this->tab === 'posts') {
            return $this->user->posts()
                ->withCount('comments')
                ->latest()
                ->get();
        }

        if ($this->tab === 'favorites') {
            return $this->user
                ->favorites()
                ->with('favoritable.user')
                ->latest()
                ->get()
                ->map(fn($fav) => $fav->favoritable);
        }

        if ($this->tab === 'shared') {
            return $this->user
                ->sharedPosts()
                ->withCount('comments')
                ->latest()
                ->get();
        }

        return collect();
    }

    #[On('followUpdated')]
    public function refreshUser()
    {
        $this->user->loadCount('followers');
    }


    public function render()
    {
        return view('livewire.user.profile-user', [
            'posts' => $this->posts,
        ]);
    }
}
