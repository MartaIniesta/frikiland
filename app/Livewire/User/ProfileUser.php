<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;

class ProfileUser extends Component
{
    public User $user;

    protected $listeners = [
        'followUpdated' => '$refresh',
    ];

    public function mount($username)
    {
        $this->user = User::where('username', $username)
            ->with(['posts.user'])
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.user.profile-user', [
            'posts' => $this->user->posts()->latest()->get(),
        ]);
    }
}
