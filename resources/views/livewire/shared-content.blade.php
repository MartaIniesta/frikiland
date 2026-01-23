<span wire:click="toggleShare" style="cursor:pointer">
    <i class="bx {{ $isShared ? 'bxs-share' : 'bx-share' }}"></i>
    <span class="like-count">
        {{ $model->shares()->count() }}
    </span>
</span>
