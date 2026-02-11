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
                <h3 class="hashtag-section-title mt-10">Respuestas de posts</h3>

                @foreach ($comments as $comment)
                    <livewire:posts.comments.comment-item :comment="$comment" :key="'hashtag-comment-' . $comment->id" />
                @endforeach
            @endif


            {{-- ================= EMPTY STATE ================= --}}
            @if ($posts->isEmpty() && $comments->isEmpty())
                <p>No hay contenido con este hashtag todavía.</p>
            @endif

        </section>
    </main>
</div>
