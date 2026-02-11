@php
    use App\Helpers\ContentFormatter;
@endphp

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
        <div class="relative" x-data="{
            dragging: false,
            showLinkInput: false,
            link: '',
            addLink() {
                if (!this.link) return;

                this.$refs.editTextarea.value += ' ' + this.link;
                this.link = '';
                this.showLinkInput = false;

                this.$refs.editTextarea.dispatchEvent(new Event('input'));
            }
        }" x-on:dragover.prevent="dragging = true"
            x-on:dragleave.prevent="dragging = false"
            x-on:drop.prevent="
                dragging = false;
                $wire.uploadMultiple('newEditingMedia', [...$event.dataTransfer.files])
            "
            :class="{ 'dragging': dragging }">

            <textarea x-ref="editTextarea" wire:model.defer="editingContent" class="w-full border rounded p-2 mb-2 textarea-comment"
                placeholder="Edita tu comentario…"></textarea>

            {{-- INPUT ENLACE --}}
            <div x-show="showLinkInput" x-transition class="link-input mb-2">
                <input type="url" class="link-input-field" placeholder="Pega un enlace (https://...)"
                    x-model="link">

                <button type="button" class="link-input-btn" @click="addLink">
                    Añadir enlace
                </button>
            </div>

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

                    <button type="button" @click="showLinkInput = !showLinkInput" title="Añadir enlace">
                        <i class="bx bx-paperclip"></i>
                    </button>
                </div>

                <div class="flex gap-2 mt-2">
                    <button wire:click="update" class="update-comment">
                        Guardar
                    </button>

                    <button wire:click="$set('editingCommentId', null)" class="cancel-comment">
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
