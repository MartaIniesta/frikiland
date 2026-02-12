<div>
    @auth
        <x-post-editor content-model="content" new-media-model="newMedia" :media="$media" submit="addPost"
            placeholder="¿Qué estás pensando?" button-text="Publicar" :avatar="Auth::user()->avatar" remove-method="removeTempMedia" />
    @endauth

    <x-infinite-scroll :has-more="$hasMore" load-method="loadPosts">
        @foreach ($posts as $post)
            @include('livewire.posts.partials.post-item', ['post' => $post])
        @endforeach

        {{-- MODAL EDITAR --}}
        @if ($editingPost)
            @include('livewire.posts.modals.edit-post')
        @endif

        {{-- MODAL ELIMINAR --}}
        @if ($deletingPost)
            @include('livewire.posts.modals.delete-post')
        @endif
    </x-infinite-scroll>

    <livewire:reports.create />
</div>
