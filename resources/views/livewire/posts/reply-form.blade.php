@auth
    <article class="create-post-box relative mt-2" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
        x-on:dragleave.prevent="dragging = false"
        x-on:drop.prevent="
        dragging = false;
        $wire.uploadMultiple(
            'newReplyMedia',
            [...$event.dataTransfer.files]
        )
    "
        :class="{ 'dragging': dragging }">
        <div class="create-post-top">
            <img src="{{ asset(Auth::user()->avatar) }}" class="create-avatar">

            <textarea wire:model.defer="replyContent" class="create-textarea" placeholder="Responder..."></textarea>
        </div>

        {{-- PREVIEW --}}
        @if ($replyMedia)
            @include('livewire.posts.media', [
                'media' => $replyMedia,
                'removable' => true,
                'removeMethod' => 'removeReplyMedia',
            ])
        @endif

        @error('replyMedia')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        <div class="create-actions">
            <div class="left-actions">
                <label for="replyMediaInput">
                    <i class="bx bx-image"></i>
                </label>

                <input type="file" id="replyMediaInput" wire:model="newReplyMedia" multiple accept="image/*,video/*"
                    hidden>
            </div>

            <div class="flex gap-2">
                <button wire:click="addReply" class="btn-post">
                    Responder
                </button>

                <button wire:click="cancelReply" class="btn-post secondary">
                    Cancelar
                </button>
            </div>
        </div>
    </article>
@endauth
