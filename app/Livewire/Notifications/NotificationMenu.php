<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\PostComment;

class NotificationMenu extends Component
{
    public array $notifications = [];
    public int $unreadCount = 0;

    public function mount()
    {
        if (!Auth::check()) return;

        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();

        $this->unreadCount = $user->unreadNotifications()->count();

        $this->notifications = $user->unreadNotifications()
            ->latest()
            ->take(4)
            ->get()
            ->map(fn($notification) => $this->mapNotification($notification))
            ->filter()
            ->values()
            ->toArray();
    }

    private function mapNotification($notification): ?array
    {
        $data = $notification->data;

        /* ========= FOLLOW ========= */
        if ($data['type'] === 'user_followed') {
            $user = User::find($data['follower_id']);
            if (!$user) return null;

            return [
                'type' => 'user_followed',
                'user' => $user,
                'url'  => route('user.profile', $user->username),
                'time' => $notification->created_at->diffForHumans(),
            ];
        }

        /* ========= FAVORITO ========= */
        if ($data['type'] === 'content_favorited') {
            $user = User::find($data['user_id']);
            if (!$user) return null;

            if ($data['model_type'] === Post::class) {
                return [
                    'type' => 'favorite_post',
                    'user' => $user,
                    'url'  => route('posts.show', $data['model_id']),
                    'time' => $notification->created_at->diffForHumans(),
                ];
            }

            if ($data['model_type'] === PostComment::class) {
                $comment = PostComment::find($data['model_id']);
                if (!$comment || !$comment->post) return null;

                return [
                    'type' => 'favorite_comment',
                    'user' => $user,
                    'url'  => route('posts.show', $comment->post->id) . '#comment-' . $comment->id,
                    'time' => $notification->created_at->diffForHumans(),
                ];
            }
        }

        /* ========= RESPUESTA ========= */
        if ($data['type'] === 'content_replied') {
            $user = User::find($data['user_id']);
            $comment = PostComment::find($data['comment_id']);

            if (!$user || !$comment || !$comment->post) return null;

            return [
                'type'    => 'content_replied',
                'user'    => $user,
                'url'     => route('posts.show', $comment->post->id) . '#comment-' . $comment->id,
                'excerpt' => $data['excerpt'],
                'time'    => $notification->created_at->diffForHumans(),
            ];
        }

        /* ========= SOLICITUD DE CHAT ========= */
        if ($data['type'] === 'chat_request') {
            $user = User::find($data['from_user_id']);
            $conversationId = $data['conversation_id'] ?? null;

            if (! $user || ! $conversationId) {
                return null;
            }

            return [
                'type' => 'chat_request',
                'user' => $user,
                'chat_request_id' => $data['chat_request_id'],
                'conversation_id' => $conversationId,
                'time' => $notification->created_at->diffForHumans(),
            ];
        }

        /* ========= CHAT RECHAZADO ========= */
        if ($data['type'] === 'chat_request_rejected') {
            $user = User::find($data['from_user_id']);

            if (! $user) return null;

            return [
                'type' => 'chat_request_rejected',
                'user' => $user,
                'time' => $notification->created_at->diffForHumans(),
            ];
        }

        if ($data['type'] === 'chat_request_accepted') {
            $user = User::find($data['from_user_id']);
            if (! $user) return null;

            return [
                'type' => 'chat_request_accepted',
                'user' => $user,
                'conversation_id' => $data['conversation_id'],
                'time' => $notification->created_at->diffForHumans(),
            ];
        }

        /* ========= CONTENIDO ELIMINADO POR ADMIN ========= */
        if ($data['type'] === 'content_removed') {

            return [
                'type' => 'content_removed',
                'content_type' => $data['content_type'],
                'excerpt' => $data['excerpt'] ?? null,
                'url'  => $data['url'] ?? route('notifications.index'),
                'time' => $notification->created_at->diffForHumans(),
                'read' => isset($notification->read_at) ? (bool) $notification->read_at : false,
            ];
        }

        return null;
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        $this->notifications = [];
        $this->unreadCount = 0;
    }

    public function render()
    {
        return view('livewire.notifications.notification-menu');
    }
}
