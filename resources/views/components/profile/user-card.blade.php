@props([
    'avatar',
    'name',
    'username',
    'bio' => null,
    'followers' => null,
    'posts' => null,
])

<div class="wrap-profile-users">
    <div>
        <div class="profile-users">
            <img src="{{ asset($avatar) }}" alt="Avatar de {{ $name }}">

            <div class="wrap-profile-content">
                <p>{{ $name }}</p>

                <div class="profile-users-sub">
                    <span style="color: var(--color-gris-oscuro);">
                        {{ '@' . $username }}
                    </span>

                    @if ($followers !== null)
                        <span>-</span>
                        <span>{{ $followers }} seguidores</span>
                    @endif

                    @if ($posts !== null)
                        <span>-</span>
                        <span>{{ $posts }} posts</span>
                    @endif
                </div>
            </div>

            @isset($actions)
                <div class="profile-users-actions">
                    {{ $actions }}
                </div>
            @endisset
        </div>

        @if ($bio)
            <p class="biography">{{ $bio }}</p>
        @endif
    </div>
</div>
