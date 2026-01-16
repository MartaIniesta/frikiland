<div>
    <x-header>
        <x-slot:search>
            <div class="search">
                <input type="text" placeholder="Buscar…" aria-label="Buscar">
                <button>
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </x-slot:search>
    </x-header>

    <main class="wrap-main">
        <section class="main-content">

            {{-- POST ORIGINAL --}}
            <article class="posts">
                <div class="wrap-profile">
                    <a href="#" class="profile-link">
                        <img src="{{ asset($post->user->avatar) }}" class="img-profile"
                            alt="Avatar de {{ $post->user->name }}">
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
                        @include('livewire.media', [
                            'media' => $post->media,
                            'removable' => false,
                        ])
                    </div>
                @endif

                <div class="content-icons">
                    <div class="content-icons-left">
                        <span>
                            <i class="bx bx-message-rounded transform-i"></i>
                            {{ $post->replies->count() }}
                        </span>

                        <span>
                            <i class="bx bx-heart"></i> 20
                        </span>

                        <span>
                            <i class="bx bx-share"></i> 2
                        </span>
                    </div>

                    <div class="content-icons-right">
                        <i class="bx bx-share"></i>
                    </div>
                </div>
            </article>

            {{-- RESPONDER --}}
            @auth
                <article class="create-post-box">
                    <div class="create-post-top">
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar de {{ Auth::user()->name }}"
                            class="create-avatar">

                        <textarea wire:model.defer="replyContent" class="create-textarea" placeholder="Responder..."></textarea>
                    </div>

                    <div class="create-actions">
                        <div class="left-actions">
                            <label for="replyMediaInput">
                                <i class="bx bx-image"></i>
                            </label>
                            <i class="bx bx-smile"></i>
                            <i class="bx bx-paperclip"></i>

                            <input type="file" id="replyMediaInput" wire:model="replyMedia" multiple hidden>
                        </div>

                        <button wire:click="addReply" class="btn-post">
                            Responder
                        </button>
                    </div>

                    @if ($replyMedia)
                        @include('livewire.media', [
                            'media' => $replyMedia,
                            'removable' => false,
                        ])
                    @endif
                </article>
            @else
                <p class="login-hint">
                    <a href="{{ route('login') }}">Inicia sesión</a> para responder.
                </p>
            @endauth

            {{-- RESPUESTAS --}}
            @foreach ($replies as $reply)
                <article class="posts">
                    <div class="wrap-profile">
                        <a href="#" class="profile-link">
                            <img src="{{ asset($reply->user->avatar) }}" class="img-profile"
                                alt="Avatar de {{ $reply->user->name }}">
                            <div class="profile-name">
                                <p>{{ $reply->user->name }}</p>
                                <span>{{ '@' . ($reply->user->username ?? 'user') }}</span>
                            </div>
                        </a>

                        <div class="right-content">
                            <span>{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <p class="text-main-content">
                        {{ $reply->content }}
                    </p>

                    @if ($reply->media)
                        <div class="content-img">
                            @include('livewire.media', [
                                'media' => $reply->media,
                                'removable' => false,
                            ])
                        </div>
                    @endif

                    <div class="content-icons">
                        <div class="content-icons-left">
                            <span>
                                <i class="bx bx-message-rounded transform-i"></i>
                                0
                            </span>

                            <span>
                                <i class="bx bx-heart"></i> 0
                            </span>

                            <span>
                                <i class="bx bx-share"></i> 0
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
</div>
