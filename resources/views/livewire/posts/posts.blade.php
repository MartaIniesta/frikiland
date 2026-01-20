<main class="wrap-main">
    <section class="main-content" x-data="{ loading: false }" x-init="window.addEventListener('scroll', () => {
        if (loading || !@entangle('hasMore')) return;

        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
            loading = true;
            $wire.loadPosts().then(() => loading = false);
        }
    })">

        {{-- CREAR POST --}}
        @auth
            @include('livewire.posts.partials.create-post')
        @endauth

        {{-- LISTADO DE POSTS --}}
        @foreach ($posts as $post)
            @include('livewire.posts.partials.post-item', ['post' => $post])
        @endforeach

        {{-- MODAL ELIMINAR --}}
        @includeWhen($deletingPost, 'livewire.posts.partials.delete-modal')

        {{-- LOADER --}}
        @if ($hasMore)
            <div class="text-center py-4 text-gray-500">
                Cargando más posts…
            </div>
        @else
            <div class="text-center py-4 text-gray-400">
                No hay más posts
            </div>
        @endif
    </section>

    {{-- MODAL EDITAR --}}
    @includeWhen($editingPost, 'livewire.posts.partials.edit-modal')
</main>
