<table class="admin-table">
    <thead>
        <tr>
            <th>User</th>
            <th>Comment</th>
            <th>Post</th>
            <th>Replies</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($comments as $comment)
            <tr>
                <td>
                    <a href="{{ route('user.profile', $comment->user->username) }}" class="admin-link">
                        {{ $comment->user->name }}
                    </a>
                </td>

                <td style="max-width:300px;">
                    <a href="{{ route('posts.show', $comment->post) }}#comment-{{ $comment->id }}" class="admin-link">

                        {{ \Illuminate\Support\Str::limit($comment->content, 80) }}

                        @if ($comment->parent_id)
                            <div style="font-size:12px; color:#888;">
                                ↳ Reply
                            </div>
                        @endif

                    </a>
                </td>

                <td>
                    <a href="{{ route('posts.show', $comment->post) }}" target="_blank" style="color:#3b82f6;">
                        #{{ $comment->post->id }}
                    </a>
                </td>

                <td>
                    @if ($comment->replies->count())
                        <span class="media-indicator">
                            <i class="bx bx-reply"></i>
                            {{ $comment->replies->count() }}
                        </span>
                    @else
                        —
                    @endif
                </td>

                <td>
                    {{ $comment->created_at->format('d/m/Y H:i') }}
                </td>

                <td>
                    <button wire:click="confirmDelete({{ $comment->id }})" class="admin-delete-btn">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
