<main class="wrap-main">
    {{-- Botón de volver automático si no estamos en el index --}}
    @if($view !== 'index')
        <button wire:click="backToIndex" class="mb-6 flex items-center text-blue-900 font-bold hover:underline">
            <i class="bx bx-left-arrow-alt text-2xl mr-1"></i> Volver al listado
        </button>
    @endif

    {{-- Switch de Vistas --}}
    @if($view === 'index')
        @include('livewire.products.partials.index')
    @elseif($view === 'show')
        @include('livewire.products.partials.show')
    @elseif($view === 'create')
        @include('livewire.products.partials.create')
    @endif
</main>