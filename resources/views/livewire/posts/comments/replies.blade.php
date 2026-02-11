@if ($comment->replies->count() > 0 && $repliesToShow === 0)
    <button wire:click="loadMoreReplies" class="text-sm text-gray-500 mt-2 cursor-pointer">
        Mostrar respuestas
    </button>
@endif

@if ($repliesToShow > 0)
    @foreach ($comment->replies->sortByDesc('created_at')->take($repliesToShow) as $reply)
        <livewire:posts.comments.comment-item :comment="$reply" :key="'comment-' . $reply->id" />
    @endforeach

    <div class="mt-2 flex gap-3">
        @if ($repliesToShow < $comment->replies->count())
            <button wire:click="loadMoreReplies" class="text-sm text-gray-400 cursor-pointer">
                Mostrar más
            </button>
        @endif

        <button wire:click="loadLessReplies" class="text-sm text-gray-400 cursor-pointer">
            Mostrar menos
        </button>
    </div>
@endif
