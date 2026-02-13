<div>
    <x-header />

    <div class="admin-posts-container">
        <x-banner-categories>
            <a href="{{ route('manage') }}" class="cat">
                ADMIN DASHBOARD
            </a>

            <a href="{{ route('admin.users') }}" class="cat">
                MANAGE USERS
            </a>

            <a class="cat active">
                REPORTS
            </a>
        </x-banner-categories>

        <div class="admin-actions-header">
            @if ($userId)
                <button wire:click="confirmAcceptAll" class="admin-accept-all-btn">
                    Aceptar todos los reportes
                </button>
            @endif
        </div>

        <div class="admin-table-wrapper">
            @include('livewire.admin.reports.partials.table')
        </div>

        <div class="admin-pagination">
            {{ $reports->links('livewire.pagination.pagination') }}
        </div>
    </div>

    @include('livewire.admin.reports.partials.confirmation-modal')
    @include('livewire.admin.reports.partials.accept-modal')
</div>
