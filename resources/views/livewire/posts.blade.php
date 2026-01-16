<main class="wrap-main">
    <section class="main-content">
        @auth
            <article class="create-post-box" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="
                dragging = false;
                $wire.uploadMultiple($event.dataTransfer.files, 'media')
            "
                :class="{ 'dragging': dragging }">
                <div class="create-post-top">
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar de {{ Auth::user()->name }}"
                        class="create-avatar">

                    <textarea wire:model.defer="content" class="create-textarea" placeholder="¿Qué estás pensando?"></textarea>
                </div>

                <div class="create-actions">
                    <div class="left-actions">
                        <label for="mediaInput">
                            <i class="bx bx-image"></i>
                        </label>
                        <i class="bx bx-smile"></i>
                        <i class="bx bx-paperclip"></i>

                        <input type="file" id="mediaInput" wire:model="media" multiple hidden>
                    </div>

                    <button wire:click="addPost" class="btn-post">
                        Publicar
                    </button>
                </div>

                {{-- PREVIEWS --}}
                @if ($media)
                    @include('livewire.media', ['media' => $media, 'removable' => false])
                @endif
            </article>
        @endauth

        {{-- LISTADO DE POSTS --}}
        @foreach ($posts as $post)
            <article class="posts">
                <div class="wrap-profile">
                    <img src="{{ asset($post->user->avatar) }}" alt="Avatar de {{ $post->user->name }}"
                        class="img-profile">

                    <div class="profile-name">
                        <p>{{ $post->user->name }}</p>
                        <span>{{ '@' . ($post->user->username ?? 'user') }}</span>
                    </div>

                    <div class="right-content">
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <p class="text-main-content">
                    {{ $post->content }}
                </p>

                @if ($post->media)
                    <div class="content-img">
                        @include('livewire.media', [
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
    </section>
</main>
