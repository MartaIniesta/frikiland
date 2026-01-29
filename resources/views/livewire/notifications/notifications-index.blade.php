<div class="notifications-page">
    <x-header />

    <x-banner-categories>
        <a class="cat active" href="{{ route('notifications.index') }}" wire:navigate>
            {{ __('NOTIFICATIONS') }}
        </a>
    </x-banner-categories>

    <div class="notifications-content">
        <ul class="notification-list-all">
            @forelse ($notifications as $notification)
                @if ($notification->data['type'] === 'user_followed')
                    @php
                        $follower = \App\Models\User::find($notification->data['follower_id']);
                    @endphp

                    @if ($follower)
                        <li class="notification-item-all {{ $notification->read_at ? 'read' : 'unread' }}">
                            <a href="{{ route('user.profile', $follower->username) }}">
                                <div class="wrap-user-notification">
                                    <img src="{{ asset($follower->avatar) }}" class="img-notification-user" width="45px"
                                        height="45px">
                                    <strong>{{ $follower->name }}</strong> te ha seguido
                                </div>
                            </a>
                            <span class="time">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </li>
                    @endif
                @endif
            @empty
                <li class="notification-item-all empty">
                    No tienes notificaciones
                </li>
            @endforelse
        </ul>
    </div>
</div>
