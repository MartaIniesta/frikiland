@php
    use App\Helpers\ContentFormatter;
@endphp

<article id="comment-{{ $comment->id }}" class="posts">

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
    @if ($isEditing)
        <div class="relative">
            <textarea wire:model.defer="editingContent" class="w-full border rounded p-2 mb-2 textarea-comment"
                placeholder="Edita tu comentario…"></textarea>

            {{-- PREVIEW MEDIA --}}
            @include('livewire.posts.media', [
                'media' => $editingMedia,
                'removable' => true,
                'removeMethod' => 'removeEditingMedia',
            ])

            <div class="create-actions mt-2">
                <div class="left-actions">
                    <label for="editMediaInput-{{ $comment->id }}" class="cursor-pointer">
                        <i class="bx bx-image"></i>
                    </label>

                    <input type="file" id="editMediaInput-{{ $comment->id }}" wire:model="newEditingMedia" multiple
                        hidden>
                </div>

                <div class="flex gap-2 mt-2">
                    <button wire:click="update" class="update-comment">
                        Guardar
                    </button>

                    <button wire:click="cancelEditing" class="cancel-comment">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @else
        <p class="text-main-content">
            {!! ContentFormatter::format($comment->content) !!}
        </p>
    @endif

    {{-- MEDIA --}}
    @if ($comment->media)
        <div class="content-img">
            @include('livewire.posts.media', [
                'media' => $comment->media,
                'removable' => false,
            ])
        </div>
    @endif

    {{-- ACCIONES (likes, fav, share...) --}}
    @include('livewire.posts.comments.actions', [
        'comment' => $comment,
    ])

    {{-- FORM REPLY --}}
    @if ($isReplying)
        @include('livewire.posts.comments.reply-form', [
            'comment' => $comment,
        ])
    @endif


    {{-- REPLIES --}}
    @include('livewire.posts.comments.replies', [
        'comment' => $comment,
    ])
</article>
