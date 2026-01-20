<article class="create-post-box relative" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
    x-on:dragleave.prevent="dragging = false"
    x-on:drop.prevent="
        dragging = false;
        $wire.uploadMultiple('newMedia', [...$event.dataTransfer.files])
    "
    :class="{ 'dragging': dragging }">

    <div class="create-post-top">
        <img src="{{ asset(Auth::user()->avatar) }}" class="create-avatar">

        <textarea wire:model.defer="content" class="create-textarea" placeholder="¿Qué estás pensando?"></textarea>
    </div>

    @include('livewire.posts.media', [
        'media' => $media,
        'removable' => true,
        'removeMethod' => 'removeTempMedia',
    ])

    <div class="create-actions">
        <div class="left-actions">
            <label for="mediaInput">
                <i class="bx bx-image"></i>
            </label>

            <input type="file" id="mediaInput" wire:model="newMedia" multiple hidden>

            <i class="bx bx-smile"></i>
            <i class="bx bx-paperclip"></i>
        </div>

        <button wire:click="addPost" class="btn-post">
            Publicar
        </button>
    </div>
</article>
