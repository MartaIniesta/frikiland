<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" x-data="{ dragging: false }"
    x-on:dragover.prevent="dragging = true" x-on:dragleave.prevent="dragging = false"
    x-on:drop.prevent="
        dragging = false;
        $wire.uploadMultiple('newEditMedia', [...$event.dataTransfer.files])
    "
    :class="{ 'dragging': dragging }">

    <div class="bg-white rounded-lg w-full max-w-lg p-6 relative">
        <h2 class="text-lg font-semibold mb-4">Editar post</h2>

        <textarea wire:model.defer="editContent" class="w-full border rounded p-2 mb-4" placeholder="Edita tu post"></textarea>

        <div class="mb-4 flex items-center gap-3">
            <label for="editMediaInput" class="cursor-pointer">
                <i class="bx bx-image text-xl"></i>
            </label>

            <input type="file" id="editMediaInput" wire:model="newEditMedia" multiple hidden>
        </div>

        @include('livewire.posts.media', [
            'media' => $editMedia,
            'removable' => true,
            'removeMethod' => 'removeEditMedia',
        ])

        <div class="flex justify-end gap-2 mt-6">
            <button wire:click="cancelEdit" class="px-4 py-2 border rounded cursor-pointer">
                Cancelar
            </button>

            <button wire:click="updatePost" class="px-4 py-2 bg-black text-white rounded cursor-pointer">
                Guardar
            </button>
        </div>
    </div>
</div>
