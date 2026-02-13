@if ($confirmingAcceptAll)
    <div class="admin-modal-overlay">
        <div class="admin-modal">
            <h3 class="admin-modal-title">
                ¿Aceptar todos los reportes?
            </h3>

            <p class="admin-modal-text">
                Se eliminará todo el contenido reportado de este usuario.
                Esta acción no se puede deshacer.
            </p>

            <div class="admin-modal-actions">
                <button wire:click="acceptAllReports" class="admin-confirm-btn">
                    Sí, aceptar todos
                </button>

                <button wire:click="$set('confirmingAcceptAll', false)" class="admin-cancel-btn">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
@endif
