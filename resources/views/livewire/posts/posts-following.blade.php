<div>
    <x-infinite-scroll :has-more="$hasMore" load-method="loadPosts">

        @if ($posts->isEmpty() && !$hasMore)
            <div class="text-center py-8 text-gray-400">
                No hay publicaciones de usuarios a los que sigues
            </div>
        @endif

        @foreach ($posts as $post)
            @include('livewire.posts.partials.post-item', ['post' => $post])
        @endforeach
    </x-infinite-scroll>

    <livewire:reports.create />
</div>
