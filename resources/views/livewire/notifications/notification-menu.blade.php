<div class="notification-menu" x-data="{ open: false }">
    <button @click="open = !open" class="notification-btn" aria-haspopup="true" :aria-expanded="open.toString()">
        <div class="hola">
            <i class="bx bx-bell notification-icon"></i>
            @if ($unreadCount > 0)
                <span class="notification-badge">
                    {{ $unreadCount }}
                </span>
            @endif
        </div>
    </button>

    <div x-show="open" x-transition x-cloak @click.away="open = false" @keydown.escape.window="open = false"
        class="notification-dropdown" role="menu">
        <p class="dropdown-title">Notificaciones</p>

        <ul class="notification-list">
            @foreach ($notifications as $notification)
                @if ($notification->data['type'] === 'user_followed')
                    @php
                        $follower = \App\Models\User::find($notification->data['follower_id']);
                    @endphp

                    @if ($follower)
                        <li class="notification-item">
                            <a href="{{ route('user.profile', $follower->username) }}">
                                <strong>{{ $follower->name }}</strong> te ha seguido
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>

        <a href="{{ route('notifications.index') }}" wire:click="markAllAsRead" class="view-more">
            Ver más →
        </a>
    </div>
</div>
