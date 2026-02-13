<div class="notification-menu" x-data="{ open: false }">
    <button @click="open = !open" class="notification-btn">
        <div class="hola">
            <i class="bx bx-bell notification-icon"></i>

            @if ($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </div>
    </button>

    <div x-show="open" x-transition x-cloak @click.away="open = false" class="notification-dropdown">
        <p class="dropdown-title">Notificaciones</p>

        <ul class="notification-list">
            @forelse ($notifications as $notification)
                <li class="notification-item unread">

                    @if ($notification['type'] === 'chat_request')
                        <a href="{{ route('chat.show', $notification['conversation_id']) }}">
                            <strong>{{ $notification['user']->name }}</strong>
                            quiere iniciar una conversación contigo
                        </a>
                    @elseif ($notification['type'] === 'chat_request_accepted')
                        <a href="{{ route('chat.show', $notification['conversation_id']) }}">
                            <strong>{{ $notification['user']->name }}</strong>
                            ha aceptado tu solicitud de conversación
                        </a>
                    @elseif ($notification['type'] === 'chat_request_rejected')
                        <span>
                            <strong>{{ $notification['user']->name }}</strong>
                            ha rechazado tu solicitud de conversación
                        </span>
                    @elseif ($notification['type'] === 'content_removed')
                        Tu {{ $notification['content_type'] }} ha sido eliminado por un administrador
                    @else
                        <a href="{{ $notification['url'] }}">
                            <strong>{{ $notification['user']->name }}</strong>

                            @if ($notification['type'] === 'user_followed')
                                te ha seguido
                            @elseif ($notification['type'] === 'favorite_post')
                                le ha gustado tu post
                            @elseif ($notification['type'] === 'favorite_comment')
                                le ha gustado tu comentario
                            @elseif ($notification['type'] === 'content_replied')
                                te ha respondido:
                                <span class="excerpt">“{{ $notification['excerpt'] }}”</span>
                            @endif
                        </a>
                    @endif

                    <span class="time">{{ $notification['time'] }}</span>
                </li>
            @empty
                <li class="notification-item empty">
                    No tienes notificaciones nuevas
                </li>
            @endforelse
        </ul>

        <a href="{{ route('notifications.index') }}" wire:click="markAllAsRead" class="view-more">
            Ver más →
        </a>
    </div>
</div>
