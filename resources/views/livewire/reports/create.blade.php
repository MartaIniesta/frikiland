<div>
    @if ($showModal)
        <div class="admin-modal-overlay">
            <div class="admin-modal">
                <h3>Reportar publicación</h3>

                <textarea wire:model="reason" class="admin-textarea" placeholder="Explica por qué reportas este contenido..."></textarea>

                @error('reason')
                    <span class="admin-error">{{ $message }}</span>
                @enderror

                <div class="admin-modal-actions">
                    <button wire:click="submit" class="admin-confirm-btn">
                        Enviar reporte
                    </button>

                    <button wire:click="$set('showModal', false)" class="admin-cancel-btn">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
