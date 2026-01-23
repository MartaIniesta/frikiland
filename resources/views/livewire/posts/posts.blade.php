<main class="wrap-main">
    <section class="main-content">
        @auth
            @include('livewire.posts.partials.create-post')
        @endauth

        @if (request('feed', 'for_you') === 'following')
            <livewire:posts.posts-following />
        @else
            <livewire:posts.posts-for-you />
        @endif
    </section>
</main>

