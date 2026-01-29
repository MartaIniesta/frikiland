<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\UserFollowed;

class FollowUser extends Component
{
    use AuthorizesRequests;

    public User $user;
    public bool $isFollowing = false;

    public function mount(User $user): void
    {
        $this->user = $user;

        if (Auth::check()) {
            $this->isFollowing = Auth::user()
                ->following()
                ->where('followed_id', $user->id)
                ->exists();
        }
    }

    public function toggleFollow()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->authorize('follow', $this->user);

        $isFollowing = Auth::user()
            ->following()
            ->where('followed_id', $this->user->id)
            ->exists();

        Auth::user()->following()->toggle($this->user->id);

        if (! $isFollowing) {
            $this->user->notify(new UserFollowed(Auth::user()));
        }

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.user.follow-user');
    }
}
