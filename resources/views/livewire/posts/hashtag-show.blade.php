@php
    use App\Helpers\ContentFormatter;
    $context = $this->getName();
@endphp

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

        <a href="{{ route('social-web.following') }}"
            class="cat {{ request()->routeIs('social-web.following') ? 'active' : '' }}">
            SIGUIENDO
        </a>
    </x-banner-categories>

    <main class="wrap-main">
        <section class="main-content">

            {{-- ================= POSTS ================= --}}
            @if ($posts->isNotEmpty())
                <h3 class="hashtag-section-title">Posts</h3>

                @foreach ($posts as $post)
                    <article class="posts">
                        @include('livewire.posts.partials.post-actions', ['post' => $post])

                        <p class="text-main-content">
                            {!! nl2br(ContentFormatter::format($post->content)) !!}
                        </p>

                        @if ($post->media)
                            <div class="content-img">
                                @include('livewire.posts.media', [
                                    'media' => $post->media,
                                    'removable' => false,
                                ])
                            </div>
                        @endif

                        <div class="content-icons">
                            <div class="content-icons-left">
                                <a href="{{ route('posts.show', $post) }}" class="comment-link">
                                    <span>
                                        <i class="bx bx-message-rounded"></i>
                                        {{ $post->comments_count }}
                                    </span>
                                </a>

                                <livewire:favorite-content :model="$post"
                                    wire:key="fav-{{ $context }}-post-{{ $post->id }}" />

                                <livewire:shared-content :model="$post"
                                    wire:key="shared-{{ $context }}-post-{{ $post->id }}" />
                            </div>
                        </div>
                    </article>
                @endforeach
            @endif


            {{-- ================= COMMENTS ================= --}}
            @if ($comments->isNotEmpty())
                <h3 class="hashtag-section-title">Respuestas de posts</h3>

                @foreach ($comments as $comment)
                    <article class="posts">
                        <div class="wrap-profile">
                            <a href="{{ route('user.profile', $comment->user->username) }}" wire:navigate
                                class="profile-link">

                                <img src="{{ asset($comment->user->avatar) }}" class="img-profile">
                                <div class="profile-name">
                                    <p>{{ $comment->user->name }}</p>
                                    <span>@ {{ $comment->user->username }}</span>
                                </div>
                            </a>

                            <div class="right-content">
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <p class="text-main-content">
                            {!! nl2br(ContentFormatter::format($comment->content)) !!}
                        </p>

                        <small class="comment-origin" style="color: var(--color-gris-claro);">
                            <a href="{{ route('posts.show', $comment->post) }}">
                                En el post de {{ $comment->post->user->name }}
                            </a>
                        </small>

                        <div class="content-icons">
                            <div class="content-icons-left">
                                <button wire:click="toggleReply({{ $comment->id }})">
                                    <span>
                                        <i class="bx bx-message-rounded"></i>
                                        {{ $comment->replies()->count() }}
                                    </span>
                                </button>

                                <livewire:favorite-content :model="$comment" :wire:key="'fav-comment-'.$comment->id" />
                                <livewire:shared-content :model="$comment" :wire:key="'share-comment-'.$comment->id" />
                            </div>

                            <div class="right-content flex items-center gap-3">
                                @can('update', $comment)
                                    <button wire:click="edit({{ $comment->id }})" class="text-gray-400 hover:text-black"
                                        title="Editar">
                                        <i class="bx bx-pencil"></i>
                                    </button>

                                    <button wire:click="delete({{ $comment->id }})"
                                        class="text-gray-400 hover:text-red-600" title="Eliminar">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </article>
                @endforeach
            @endif


            {{-- ================= EMPTY STATE ================= --}}
            @if ($posts->isEmpty() && $comments->isEmpty())
                <p>No hay contenido con este hashtag todavía.</p>
            @endif

        </section>
    </main>
</div>
