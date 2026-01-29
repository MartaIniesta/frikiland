<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationMenu extends Component
{
    public $notifications = [];
    public int $unreadCount = 0;

    public function mount()
    {
        if (!Auth::check()) return;

        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(5)
            ->get();

        $this->unreadCount = Auth::user()
            ->unreadNotifications()
            ->count();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        $this->unreadCount = 0;

        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notifications.notification-menu');
    }
}
