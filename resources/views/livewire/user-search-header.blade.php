<div class="search-wrapper">
    <div class="search">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar usuarios…" aria-label="Buscar">
        <button type="button">
            <i class="bx bx-search"></i>
        </button>
    </div>

    @if (strlen($search) >= 2)
        <div class="search-result">
            @forelse($users as $user)
                <a href="{{ route('user.profile', $user->username) }}" class="search-user">
                    <img src="{{ asset($user->avatar) }}">
                    <div class="name-search">
                        <p>{{ $user->name }}</p>
                        <span>{{ $user->username }}</span>
                    </div>
                </a>
            @empty
                <div class="search-empty">
                    No hay usuarios con ese nombre
                </div>
            @endforelse
        </div>
    @endif
</div>
