<div class="content-icons">
    <div class="content-icons-left">
        <button wire:click="toggleReply({{ $comment->id }})">
            <span>
                <i class="bx bx-message-rounded"></i>
                {{ $comment->replies()->count() }}
            </span>
        </button>

        <livewire:favorite-content :model="$comment" :wire:key="'fav-comment-'.$comment->id" />
        <livewire:shared-content :model="$comment" :wire:key="'share-comment-'.$comment->id" />
    </div>

    <div class="right-content flex items-center gap-3">
        @can('update', $comment)
            <button wire:click="edit({{ $comment->id }})" class="text-gray-400 hover:text-black" title="Editar">
                <i class="bx bx-pencil"></i>
            </button>

            <button wire:click="delete({{ $comment->id }})" class="text-gray-400 hover:text-red-600" title="Eliminar">
                <i class="bx bx-trash"></i>
            </button>
        @endcan
    </div>
</div>
