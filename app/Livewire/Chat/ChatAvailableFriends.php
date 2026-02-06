<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChatAvailableFriends extends Component
{
    public function getUsersProperty()
    {
        return User::availableForChat(Auth::user())->get();
    }

    public function render()
    {
        return view('livewire.chat.chat-available-friends');
    }
}
