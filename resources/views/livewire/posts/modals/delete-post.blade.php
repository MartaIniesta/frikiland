<div class="delete-modal-overlay">
    <div class="delete-modal">
        <h2 class="delete-title">Eliminar post</h2>

        <p class="delete-text">
            ¿Confirmas que quieres eliminar este post?
        </p>

        <div class="delete-actions">
            <button wire:click="cancelDelete" class="btn-delete-cancel">
                Cancelar
            </button>

            <button wire:click="deletePost" class="btn-delete-confirm">
                Eliminar permanentemente
            </button>
        </div>
    </div>
</div>
