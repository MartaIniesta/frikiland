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

    {{-- PERFIL --}}
    <div class="wrap-profile-all">
        <div class="wrapper-profile">
            <div class="profile-users-blade">
                <img src="{{ asset($user->avatar) }}" class="profile-avatar" width="100px" height="100px">

                <div class="wrap-profile-content">
                    <p>{{ $user->name }}</p>

                    <div class="profile-users-sub">
                        <span>{{ '@' . $user->username }}</span>
                        <span>-</span>
                        <span>{{ $user->followers_count }} seguidores</span>
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

            {{-- TABS --}}
            <div class="wrap-profile-cat cursor-pointer">
                <a wire:click.prevent="setTab('posts')" class="cat {{ $tab === 'posts' ? 'active' : '' }}">
                    Posts
                </a>

                <a wire:click.prevent="setTab('shared')" class="cat {{ $tab === 'shared' ? 'active' : '' }}">
                    Compartidos
                </a>

                <a wire:click.prevent="setTab('favorites')" class="cat {{ $tab === 'favorites' ? 'active' : '' }}">
                    Favoritos
                </a>
            </div>
        </div>
    </div>

    {{-- CONTENIDO --}}
    <main class="wrap-main">
        <section class="main-content">

            @if ($posts->isEmpty())
                <p class="no-posts">
                    {{ $tab === 'favorites' ? 'Este usuario tiene los favoritos privados.' : 'No hay contenido para mostrar.' }}
                </p>
            @else
                @foreach ($posts as $item)
                    {{-- POST --}}
                    @if ($item instanceof \App\Models\Post)
                        <article class="posts">
                            <div class="wrap-profile">
                                <a href="{{ route('user.profile', $item->user->username) }}" class="profile-link">
                                    <img src="{{ asset($item->user->avatar) }}" class="img-profile">

                                    <div class="profile-name">
                                        <p>{{ $item->user->name }}</p>
                                        <span>{{ '@' . $item->user->username }}</span>
                                    </div>
                                </a>

                                <div class="right-content">
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <p class="text-main-content">{{ $item->content }}</p>

                            @if ($item->media)
                                <div class="content-img">
                                    @include('livewire.posts.media', [
                                        'media' => $item->media,
                                        'removable' => false,
                                    ])
                                </div>
                            @endif

                            <div class="content-icons">
                                <div class="content-icons-left">
                                    <a href="{{ route('posts.show', $item) }}">
                                        <span>
                                            <i class="bx bx-message-rounded"></i>
                                            {{ $item->comments_count }}
                                        </span>
                                    </a>

                                    <livewire:favorite-content :model="$item" :wire:key="'fav-post-'.$item->id" />

                                    <livewire:posts.shared-post :post="$item"
                                        :wire:key="'shared-post-'.$item->id" />
                                </div>
                            </div>
                        </article>
                    @endif

                    {{-- COMENTARIO --}}
                    @if ($item instanceof \App\Models\PostComment)
                        <article class="posts">
                            <div class="wrap-profile">
                                <a href="{{ route('user.profile', $item->user->username) }}" class="profile-link">
                                    <img src="{{ asset($item->user->avatar) }}" class="img-profile">

                                    <div class="profile-name">
                                        <p>{{ $item->user->name }}</p>
                                        <span>{{ '@' . $item->user->username }}</span>
                                    </div>
                                </a>

                                <div class="right-content">
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <p class="text-main-content">{{ $item->content }}</p>

                            @if ($item->media)
                                <div class="content-img">
                                    @include('livewire.posts.media', [
                                        'media' => $item->media,
                                        'removable' => false,
                                    ])
                                </div>
                            @endif

                            <div class="content-icons">
                                <div class="content-icons-left">
                                    <a href="{{ route('posts.show', $item->post) }}#comment-{{ $item->id }}"
                                        class="comment-link" title="Ver comentario en el post">
                                        <span>
                                            <i class="bx bx-message-rounded"></i>
                                            {{ $item->replies()->count() }}
                                        </span>
                                    </a>

                                    <livewire:favorite-content :model="$item" :wire:key="'fav-comment-'.$item->id" />
                                </div>
                            </div>
                        </article>
                    @endif
                @endforeach
            @endif

        </section>
    </main>
</div>
