<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentRemovedNotification extends Notification
{
    use Queueable;

    public $contentType;
    public $excerpt;

    public function __construct($type, $excerpt)
    {
        $this->contentType = $type;
        $this->excerpt = $excerpt;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'content_removed',
            'content_type' => $this->contentType,
            'excerpt' => $this->excerpt,
            'message' => "Tu {$this->contentType} ha sido eliminado por un administrador.",
            'url' => route('notifications.index'),
        ];
    }
}
