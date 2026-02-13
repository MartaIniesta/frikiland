<div>
    <x-header>
        <x-slot:search>
            <livewire:user-search-header />
        </x-slot:search>
    </x-header>

    <x-banner-categories>
        <a href="{{ route('social-web.for-you') }}"
            class="cat {{ request()->routeIs('social-web.for-you') ? 'active' : '' }}">
            PARA TI
        </a>

        <a class="cat active">
            POST
        </a>
    </x-banner-categories>

    <main class="wrap-main">
        <section class="main-content">
            {{-- POST --}}
            @include('livewire.posts.partials.post-item', ['post' => $post])

            {{-- COMENTARIOS --}}
            <livewire:posts.post-comments :post-id="$post->id" wire:key="post-comments-{{ $post->id }}" />

            @if ($editing)
                @include('livewire.posts.modals.edit-post')
            @endif

            @if ($confirmingDelete)
                @include('livewire.posts.modals.delete-post')
            @endif
        </section>
    </main>

    <livewire:reports.create />
</div>
