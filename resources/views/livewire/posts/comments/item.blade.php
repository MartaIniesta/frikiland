<article class="posts" wire:key="comment-{{ $comment->id }}">

    {{-- HEADER --}}
    <div class="wrap-profile">
        <a href="{{ route('user.profile', $comment->user->username) }}" class="profile-link">
            <img src="{{ asset($comment->user->avatar) }}" class="img-profile">
            <div class="profile-name">
                <p>{{ $comment->user->name }}</p>
                <span>{{ '@' . $comment->user->username }}</span>
            </div>
        </a>

        <div class="right-content flex items-center gap-3">
            <span>{{ $comment->created_at->diffForHumans() }}</span>
        </div>
    </div>

    {{-- CONTENIDO --}}
    @if ($editingCommentId === $comment->id)
        <div class="relative" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
            x-on:dragleave.prevent="dragging = false"
            x-on:drop.prevent="
            dragging = false;
            $wire.uploadMultiple('newEditingMedia', [...$event.dataTransfer.files])
        "
            :class="{ 'dragging': dragging }">
            <textarea wire:model.defer="editingContent" class="w-full border rounded p-2 mb-2 textarea-comment"
                placeholder="Edita tu comentario…"></textarea>

            <div class="mb-2">
                <label for="editMediaInput-{{ $comment->id }}" class="cursor-pointer">
                    <i class="bx bx-image"></i>
                </label>

                <input type="file" id="editMediaInput-{{ $comment->id }}" wire:model="newEditingMedia" multiple
                    hidden>
            </div>

            @include('livewire.posts.media', [
                'media' => $editingMedia,
                'removable' => true,
                'removeMethod' => 'removeEditingMedia',
            ])

            <div class="flex gap-2 mt-2">
                <button wire:click="update" class="px-3 py-1 rounded update-comment">
                    Guardar
                </button>

                <button wire:click="$set('editingCommentId', null)" class="px-3 py-1 rounded cancel-comment">
                    Cancelar
                </button>
            </div>
        </div>
    @else
        <p class="text-main-content">{{ $comment->content }}</p>
    @endif

    @if ($comment->media)
        <div class="content-img">
            @include('livewire.posts.media', [
                'media' => $comment->media,
                'removable' => false,
            ])
        </div>
    @endif

    {{-- ACCIONES --}}
    @include('livewire.posts.comments.actions', [
        'comment' => $comment,
    ])

    {{-- FORM REPLY --}}
    @if ($replyingToId === $comment->id)
        @include('livewire.posts.comments.reply-form', [
            'comment' => $comment,
        ])
    @endif

    {{-- REPLIES --}}
    @include('livewire.posts.comments.replies', [
        'comment' => $comment,
    ])
</article>
