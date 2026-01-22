<span class="share-btn" wire:click="toggleShare" style="cursor:pointer">
    <i class="bx {{ $isShared ? 'bxs-share' : 'bx-share' }}"></i>

    <span class="share-count">
        {{ $post->sharedBy()->count() }}
    </span>
</span>
