<article class="posts ml-{{ $level * 4 }}">
    <div class="wrap-profile">
        <a href="#" class="profile-link">
            <img src="{{ asset($comment->user->avatar) }}" class="img-profile" alt="Avatar de {{ $comment->user->name }}">

            <div class="profile-name">
                <p>{{ $comment->user->name }}</p>
                <span>{{ '@' . ($comment->user->username ?? 'user') }}</span>
            </div>
        </a>

        <div class="right-content">
            <span>{{ $comment->created_at->diffForHumans() }}</span>
        </div>
    </div>

    <p class="text-main-content">
        {{ $comment->content }}
    </p>

    @if ($comment->media)
        <div class="content-img">
            @include('livewire.posts.media', [
                'media' => $comment->media,
                'removable' => false,
            ])
        </div>
    @endif

    {{-- ICONOS --}}
    <div class="content-icons">
        <div class="content-icons-left">
            <span wire:click="startReply({{ $comment->id }})" class="cursor-pointer">
                <i class="bx bx-message-rounded"></i>
            </span>

            <span><i class="bx bx-heart"></i> 20</span>
            <span><i class="bx bx-share"></i> 2</span>
        </div>
    </div>

    {{-- FORMULARIO DE RESPUESTA --}}
    @if ($replyingTo === $comment->id)
        @include('livewire.posts.reply-form')
    @endif

    {{-- RESPUESTAS HIJAS --}}
    @if (!$isRoot)
        @php
            $visibleReplies = $this->getVisibleReplies($comment);
            $visibleCount = $visibleReplies->count();
            $totalReplies = $comment->replies()->count();
        @endphp

        @foreach ($visibleReplies as $reply)
            @include('livewire.posts.comment', [
                'comment' => $reply,
                'level' => $level + 1,
                'isRoot' => false,
            ])
        @endforeach

        @if ($totalReplies > 0)
            <div class="mt-2 flex gap-3 text-sm text-gray-500">
                @if ($visibleCount < $totalReplies)
                    <button wire:click="showMoreReplies({{ $comment->id }})" class="hover:underline">
                        Mostrar más
                    </button>
                @endif

                @if ($visibleCount > 0)
                    <button wire:click="showLessReplies({{ $comment->id }})" class="hover:underline">
                        Mostrar menos
                    </button>
                @endif
            </div>
        @endif
    @endif
</article>
