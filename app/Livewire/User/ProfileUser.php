<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileUser extends Component
{
    public User $user;
    public string $tab = 'posts';

    protected $queryString = [
        'tab' => ['except' => 'posts'],
    ];

    public function mount($username)
    {
        $this->user = User::where('username', $username)->firstOrFail();
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
            return $this->user->favoritePosts()
                ->withCount('comments')
                ->latest()
                ->get();
        }

        return collect();
    }

    public function render()
    {
        return view('livewire.user.profile-user', [
            'posts' => $this->posts,
        ]);
    }
}
