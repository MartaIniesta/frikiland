<div class="content-icons">
    <div class="content-icons-left">
        <button wire:click="toggleReply">
            <span>
                <i class="bx bx-message-rounded"></i>
                {{ $comment->replies->count() }}
            </span>
        </button>

        <livewire:favorite-content :model="$comment" :wire:key="'fav-comment-'.$comment->id" />
        <livewire:shared-content :model="$comment" :wire:key="'share-comment-'.$comment->id" />
    </div>

    <div class="right-content flex items-center gap-3">
        @if (auth()->id() === $comment->user_id)
            <button wire:click="startEditing">
                <i class="bx bx-pencil"></i>
            </button>

            <button wire:click="delete">
                <i class="bx bx-trash comment-icon-trash"></i>
            </button>
        @endif
    </div>
</div>
