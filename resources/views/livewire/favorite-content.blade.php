<span wire:click="toggle" style="cursor:pointer">
    <i class="bx {{ $isFavorite ? 'bxs-heart text-red-500' : 'bx-heart' }}"></i>
    <span class="like-count">
        {{ $model->favorites()->count() }}
    </span>
</span>
