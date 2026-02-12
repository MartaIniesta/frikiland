@if ($confirmingDelete)
    <div class="admin-modal-overlay">
        <div class="admin-modal">
            <p>Are you sure you want to delete this comment?</p>

            <div class="admin-modal-actions">
                <button wire:click="deleteComment" class="admin-confirm-btn">
                    Yes, delete
                </button>

                <button wire:click="$set('confirmingDelete', null)" class="admin-cancel-btn">
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endif
