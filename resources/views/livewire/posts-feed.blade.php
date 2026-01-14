<div x-data x-init="window.addEventListener('scroll', () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        @this.loadPosts();
    }
})">
    @foreach ($posts as $post)
        <div class="post">
            <p>{{ $post['content'] }}</p>
            @if ($post['media'])
                @foreach ($post['media'] as $media)
                    @if (Str::endsWith($media, ['mp4', 'mov', 'avi']))
                        <video controls>
                            <source src="{{ asset('storage/posts/' . $media) }}" type="video/mp4">
                        </video>
                    @else
                        <img src="{{ asset('storage/posts/' . $media) }}">
                    @endif
                @endforeach
            @endif
        </div>
    @endforeach
</div>
