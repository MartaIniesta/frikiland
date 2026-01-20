<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold mb-2">Eliminar post</h2>
        <p class="text-gray-600 mb-4">
            ¿Confirmas que quieres eliminar este post?
        </p>

        <div class="flex justify-end gap-2">
            <button wire:click="cancelDelete" class="px-4 py-2 border rounded cursor-pointer">
                Cancelar
            </button>

            <button wire:click="deletePost" class="px-4 py-2 bg-red-600 text-white rounded cursor-pointer">
                Eliminar permanentemente
            </button>
        </div>
    </div>
</div>
