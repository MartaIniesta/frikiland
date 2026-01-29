<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsIndex extends Component
{
    public $notifications = [];

    public function mount()
    {
        if (!Auth::check()) return;

        $this->notifications = Auth::user()
            ->notifications()
            ->latest()
            ->get();

        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.notifications.notifications-index');
    }
}
