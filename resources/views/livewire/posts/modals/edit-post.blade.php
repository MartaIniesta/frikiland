<div class="edit-modal-overlay" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
    x-on:dragleave.prevent="dragging = false"
    x-on:drop.prevent="
        dragging = false;
        $wire.uploadMultiple('newEditMedia', [...$event.dataTransfer.files])
    "
    :class="{ 'dragging': dragging }">

    <div class="edit-modal">

        <h2 class="edit-title">Editar post</h2>

        <textarea wire:model.defer="editContent" class="edit-textarea" placeholder="Edita tu post"></textarea>

        <div class="edit-upload-row">
            <label for="editMediaInput" class="edit-upload-btn">
                <i class="bx bx-image"></i>
                Añadir imagen
            </label>

            <input type="file" id="editMediaInput" wire:model="newEditMedia" multiple hidden>
        </div>

        @include('livewire.posts.media', [
            'media' => $editMedia,
            'removable' => true,
            'removeMethod' => 'removeEditMedia',
        ])

        <div class="edit-actions">
            <button wire:click="cancelEdit" class="btn-cancel">
                Cancelar
            </button>

            <button wire:click="updatePost" class="btn-save">
                Guardar
            </button>
        </div>
    </div>
</div>
