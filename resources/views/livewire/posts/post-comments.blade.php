<div class="post-comments">

    @include('livewire.posts.comments.create')

    <div class="space-y-6">
        @foreach ($comments as $comment)
            <livewire:posts.comments.comment-item :comment="$comment" :key="'comment-' . $comment->id" />
        @endforeach
    </div>
</div>
