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
            <article class="create-post-box relative" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="
                    dragging = false;
                    $wire.uploadMultiple('newMedia', [...$event.dataTransfer.files])
                "
                :class="{ 'dragging': dragging }">

                <div class="create-post-top">
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar de {{ Auth::user()->name }}"
                        class="create-avatar">

                    <textarea wire:model.defer="content" class="create-textarea" placeholder="¿Qué estás pensando?"></textarea>
                </div>

                @include('livewire.posts.media', [
                    'media' => $media,
                    'removable' => true,
                    'removeMethod' => 'removeTempMedia',
                ])

                @error('media')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="create-actions">
                    <div class="left-actions">
                        <label for="mediaInput">
                            <i class="bx bx-image"></i>
                        </label>

                        <input type="file" id="mediaInput" wire:model="newMedia" multiple accept="image/*,video/*"
                            hidden>

                        <i class="bx bx-smile"></i>
                        <i class="bx bx-paperclip"></i>
                    </div>

                    <button wire:click="addPost" class="btn-post">
                        Publicar
                    </button>
                </div>
            </article>
        @endauth

        {{-- LISTADO DE POSTS --}}
        @foreach ($posts as $post)
            <article class="posts">
                <div class="wrap-profile">
                    <a href="#" class="profile-link">
                        <img src="{{ asset($post->user->avatar) }}" alt="Avatar de {{ $post->user->name }}"
                            class="img-profile">

                        <div class="profile-name">
                            <p>{{ $post->user->name }}</p>
                            <span>{{ '@' . ($post->user->username ?? 'user') }}</span>
                        </div>
                    </a>

                    <div class="right-content">
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <p class="text-main-content">
                    {{ $post->content }}
                </p>

                @if ($post->media)
                    <div class="content-img">
                        @include('livewire.posts.media', [
                            'media' => $post->media,
                            'removable' => false,
                        ])
                    </div>
                @endif

                {{-- ICONOS --}}
                <div class="content-icons">
                    <div class="content-icons-left">

                        <a href="{{ route('posts.show', $post) }}" class="comment-link">
                            <span>
                                <i class="bx bx-message-rounded"></i>
                                {{ $post->replies->count() }}
                            </span>
                        </a>

                        <span class="like-btn">
                            <i class="bx bx-heart"></i>
                            <span class="like-count">20</span>
                        </span>

                        <span class="share-btn">
                            <i class="bx bx-share"></i>
                            <span class="share-count">2</span>
                        </span>

                    </div>

                    <div class="content-icons-right">
                        <i class="bx bx-share"></i>
                    </div>
                </div>
            </article>
        @endforeach

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
</main>
