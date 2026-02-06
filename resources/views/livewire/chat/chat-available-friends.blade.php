<div class="chat-window friends">
    <div class="title-friend-chat">
        <p>Contactos disponibles - {{ $this->users->count() }}</p>
    </div>

    <div class="friend-connect">
        @forelse ($this->users as $user)
            <div class="friend-item">
                <a href="{{ route('user.profile', $user->username) }}" class="friend-profile">
                    <img src="{{ asset($user->avatar) }}">
                    <div class="friend-name">
                        <strong>{{ $user->name }}</strong>
                        <span>{{ $user->username }}</span>
                    </div>
                </a>

                <a href="{{ route('chat.start', $user) }}" class="btn-friend">
                    Iniciar conversación
                </a>
            </div>
        @empty
            <p class="muted">No hay contactos disponibles</p>
        @endforelse
    </div>
</div>
