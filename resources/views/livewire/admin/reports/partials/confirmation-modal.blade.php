@if ($confirmingAction)
    <div class="admin-modal-overlay">
        <div class="admin-modal">

            <h3 class="admin-modal-title">
                {{ $actionType === 'accept' ? 'Eliminar publicación' : 'Descartar reporte' }}
            </h3>

            <p class="admin-modal-text">
                @if ($actionType === 'accept')
                    Se eliminará la publicación reportada y el reporte quedará resuelto.
                @else
                    El reporte será marcado como revisado.
                @endif
            </p>

            <div class="admin-modal-actions">

                <button wire:click="executeAction" class="admin-confirm-btn">
                    Sí, continuar
                </button>

                <button wire:click="$set('confirmingAction', null)" class="admin-cancel-btn">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
@endif
