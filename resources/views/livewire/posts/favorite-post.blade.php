<span class="like-btn" wire:click="toggleFavorite" style="cursor:pointer">
    <i class="bx {{ $isFavorite ? 'bxs-heart text-red-500' : 'bx-heart' }}"></i>

    <span class="like-count">
        {{ $post->favoritedBy()->count() }}
    </span>
</span>
