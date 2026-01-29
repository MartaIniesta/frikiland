<article class="create-post-box relative" wire:key="reply-form-{{ $comment->id }}" x-data="{
    dragging: false,
    showLinkInput: false,
    link: '',
    addLink() {
        if (!this.link) return;

        this.$refs.replyTextarea.value += ' ' + this.link;
        this.link = '';
        this.showLinkInput = false;

        this.$refs.replyTextarea.dispatchEvent(new Event('input'));
    }
}"
    x-on:dragover.prevent="dragging = true" x-on:dragleave.prevent="dragging = false"
    x-on:drop.prevent="
        dragging = false;
        $wire.uploadMultiple('newReplyMedia', [...$event.dataTransfer.files])
    "
    :class="{ 'dragging': dragging }">
    <div class="create-post-top">
        <img src="{{ asset(Auth::user()->avatar) }}" class="create-avatar">

        <textarea x-ref="replyTextarea" wire:model.defer="replyContent" class="create-textarea"
            placeholder="Escribe una respuesta…"></textarea>
    </div>

    @include('livewire.posts.media', [
        'media' => $replyMedia,
        'removable' => true,
        'removeMethod' => 'removeReplyMedia',
    ])

    <div x-show="showLinkInput" x-transition class="link-input">
        <input type="url" class="link-input-field" placeholder="Pega un enlace (https://...)" x-model="link">

        <button type="button" class="link-input-btn" @click="addLink">
            Añadir enlace
        </button>
    </div>

    <div class="create-actions">
        <div class="left-actions">
            <label for="replyMediaInput-{{ $comment->id }}">
                <i class="bx bx-image"></i>
            </label>

            <input type="file" id="replyMediaInput-{{ $comment->id }}" wire:model="newReplyMedia" multiple hidden>

            <button type="button" @click="showLinkInput = !showLinkInput" title="Añadir enlace">
                <i class="bx bx-paperclip"></i>
            </button>
        </div>

        <button wire:click="addReply" class="btn-post">
            Responder
        </button>
    </div>
</article>
