<div class="wrap-profile">
    <a href="{{ route('user.profile', $post->user->username) }}" class="profile-link">
        <img src="{{ asset($post->user->avatar) }}" class="img-profile">

        <div class="profile-name">
            <p>{{ $post->user->name }}</p>
            <span>{{ '@' . $post->user->username }}</span>
        </div>
    </a>

    <div class="right-content">
        <span>{{ $post->created_at->diffForHumans() }}</span>

        @can('update', $post)
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="dots-vertical">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>

                <div x-show="open" x-transition @click.away="open = false" class="modal-post">

                    <button wire:click="edit({{ $post->id }})" @click="open = false">
                        <i class="bx bx-pencil"></i> Editar
                    </button>

                    <button class="btn-delete-post" wire:click="confirmDelete({{ $post->id }})" @click="open = false">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        @endcan
    </div>
</div>
