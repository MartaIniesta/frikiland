@php
    use App\Helpers\ContentFormatter;
    $context = $this->getName();
@endphp

<article class="posts" wire:key="post-{{ $context }}-{{ $post->id }}">
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

            <livewire:favorite-content :model="$post" wire:key="fav-{{ $context }}-post-{{ $post->id }}" />

            <livewire:shared-content :model="$post" wire:key="shared-{{ $context }}-post-{{ $post->id }}" />
        </div>
    </div>
</article>
