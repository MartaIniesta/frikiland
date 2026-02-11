<div class="post-comments">
    @auth
        <div class="mb-6">
            <x-post-editor content-model="content" new-media-model="newMedia" :media="$media" submit="addComment"
                placeholder="Escribe un comentario…" button-text="Responder" :avatar="Auth::user()->avatar" remove-method="removeMedia" />
        </div>
    @endauth

    <div class="space-y-6">
        @foreach ($comments as $comment)
            <livewire:posts.comments.comment-item :comment="$comment" :key="'comment-' . $comment->id" />
        @endforeach
    </div>
</div>
