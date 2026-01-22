<div>
    <x-header>
        <x-slot:menu>
            <div class="menu">
                <a href="{{ route('social-web') }}">
                    <i class="bx bx-left-arrow-alt return"></i>
                </a>
            </div>
        </x-slot:menu>

        <x-slot:search>
            <div class="search">
                <input type="text" placeholder="Buscar…" aria-label="Buscar">
                <button>
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </x-slot:search>
    </x-header>

    <div class="wrap-profile-all">
        <div class="wrapper-profile">
            <div class="profile-users-blade">
                <img src="{{ asset($user->avatar) }}" class="profile-avatar" width="100px" height="100px">

                <div class="wrap-profile-content">
                    <p>{{ $user->name }}</p>

                    <div class="profile-users-sub">
                        <span>{{ '@' . $user->username }}</span>
                        <span>-</span>
                        <span>{{ $user->followers()->count() }} seguidores</span>
                        <span>-</span>
                        <span>{{ $user->posts()->count() }} posts</span>
                    </div>
                </div>

                @auth
                    @if (auth()->id() !== $user->id)
                        <div class="profile-follow">
                            <livewire:user.follow-user :user="$user" />
                        </div>
                    @endif
                @endauth
            </div>

            <div class="bio-profile-user">
                <p>{{ $user->bio }}</p>
            </div>

            <div class="wrap-profile-cat">
                <a href="#" class="cat active">
                    Posts
                </a>

                <a href="#" class="cat">
                    Compartidos
                </a>

                <a href="#" class="cat">
                    Favoritos
                </a>
            </div>
        </div>
    </div>

    <main class="wrap-main">
        <!-- CONTENIDO -->
        <section class="main-content">
            @forelse ($posts as $post)
                <article class="posts">
                    <div class="wrap-profile">
                        <a href="{{ route('user.profile', $post->user->username) }}" class="profile-link">
                            <img src="{{ asset($post->user->avatar) }}" class="img-profile">

                            <div class="profile-name">
                                <strong>{{ $post->user->name }}</strong>
                                <span>{{ '@' . $post->user->username }}</span>
                            </div>
                        </a>

                        <div class="right-content">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="text-main-content">
                        <p>{{ $post->content }}</p>
                    </div>

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
                                    {{ $post->comments_count }}
                                </span>
                            </a>

                            <livewire:posts.favorite-post :post="$post" :wire:key="'favorite-post-'.$post->id" />
                        </div>
                    </div>
                </article>
            @empty
                <p class="no-posts">Este usuario aún no ha publicado nada.</p>
            @endforelse
        </section>
    </main>
</div>
