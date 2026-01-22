<article class="posts" wire:key="post-{{ $post->id }}">
    @include('livewire.posts.partials.post-actions', ['post' => $post])

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
                    {{ $post->comments_count }}
                </span>
            </a>

            <livewire:posts.favorite-post :post="$post" :wire:key="'favorite-post-'.$post->id" />

            <livewire:posts.shared-post :post="$post" :wire:key="'shared-'.$post->id" />
        </div>
    </div>

</article>
