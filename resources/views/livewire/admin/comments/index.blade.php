<div>
    <x-header />

    <div class="admin-posts-container">

        <x-banner-categories>
            <a href="{{ route('manage') }}" class="cat">
                ADMIN DASHBOARD
            </a>

            <a href="{{ route('admin.comments') }}" class="cat active">
                MANAGE COMMENTS
            </a>
        </x-banner-categories>

        <div class="admin-search-wrapper">
            <input type="text" wire:model.live="search" placeholder="Search comments..." class="admin-search-input">
            <i class="bx bx-search"></i>
        </div>

        <div class="admin-table-wrapper">
            @include('livewire.admin.comments.partials.table')
        </div>

        <div class="admin-pagination">
            {{ $comments->links() }}
        </div>

        @include('livewire.admin.comments.partials.delete-modal')
    </div>
</div>
