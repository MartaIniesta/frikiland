<section x-data="{ loading: false }" x-init="window.addEventListener('scroll', () => {
    if (loading || !@entangle('hasMore')) return;

    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) {
        loading = true;
        $wire.loadPosts().then(() => loading = false);
    }
})">
    @if ($posts->isEmpty() && !$hasMore)
        <div class="text-center py-8 text-gray-400">
            No hay publicaciones de usuarios a los que sigues
        </div>
    @endif

    @foreach ($posts as $post)
        @include('livewire.posts.partials.post-item', ['post' => $post])
    @endforeach

    @if ($hasMore)
        <div class="text-center py-4 text-gray-500">
            Cargando más posts…
        </div>
    @endif
</section>
