<div class="post-comments">

    {{-- CREAR COMENTARIO --}}
    @auth
        <div class="mb-6">
            <article class="create-post-box relative" x-data="{ dragging: false }" x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="
                    dragging = false;
                    $wire.uploadMultiple('newMedia', [...$event.dataTransfer.files])
                "
                :class="{ 'dragging': dragging }">

                <div class="create-post-top">
                    <img src="{{ asset(Auth::user()->avatar) }}" class="create-avatar">
                    <textarea wire:model.defer="content" class="create-textarea" placeholder="Escribe un comentario…"></textarea>
                </div>

                {{-- PREVIEW MEDIA --}}
                @include('livewire.posts.media', [
                    'media' => $media,
                    'removable' => true,
                    'removeMethod' => 'removeMedia',
                ])

                <div class="create-actions">
                    <div class="left-actions">
                        <label for="commentMediaInput">
                            <i class="bx bx-image"></i>
                        </label>
                        <input type="file" id="commentMediaInput" wire:model="newMedia" multiple hidden>
                    </div>

                    <button wire:click="addComment" class="btn-post">
                        Responder
                    </button>
                </div>
            </article>
        </div>
    @endauth

    {{-- LISTADO DE COMENTARIOS --}}
    <div class="space-y-6">

        @forelse ($comments as $comment)
            <article class="posts" wire:key="comment-{{ $comment->id }}">

                {{-- HEADER --}}
                <div class="wrap-profile">
                    <a href="{{ route('user.profile', $comment->user->username) }}" class="profile-link">
                        <img src="{{ asset($comment->user->avatar) }}" class="img-profile">

                        <div class="profile-name">
                            <p>{{ $comment->user->name }}</p>
                            <span>{{ '@' . $comment->user->username }}</span>
                        </div>
                    </a>

                    <div class="right-content">
                        <span>{{ $comment->created_at->diffForHumans() }}</span>

                        @can('update', $comment)
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="dots-vertical">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>

                                <div x-show="open" @click.away="open = false" class="modal-post">
                                    <button wire:click="edit({{ $comment->id }})">
                                        <i class="bx bx-pencil"></i> Editar
                                    </button>
                                    <button wire:click="delete({{ $comment->id }})">
                                        <i class="bx bx-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>

                {{-- CONTENIDO --}}
                @if ($editingCommentId === $comment->id)
                    <textarea wire:model.defer="editingContent" class="create-textarea mb-2"></textarea>

                    @include('livewire.posts.media', [
                        'media' => $editingMedia,
                        'removable' => true,
                        'removeMethod' => 'removeEditingMedia',
                    ])

                    <div class="flex gap-2 mt-2">
                        <button wire:click="update" class="btn-post">Guardar</button>
                        <button wire:click="$set('editingCommentId', null)" class="btn-post">Cancelar</button>
                    </div>
                @else
                    <p class="text-main-content">{{ $comment->content }}</p>

                    @if ($comment->media)
                        <div class="content-img">
                            @include('livewire.posts.media', [
                                'media' => $comment->media,
                                'removable' => false,
                            ])
                        </div>
                    @endif
                @endif

                {{-- ACCIONES --}}
                <div class="content-icons">
                    {{-- RESPUESTAS --}}
                    <div class="content-icons-left">
                        <span>
                            <button wire:click="toggleReply({{ $comment->id }})">
                                <i class="bx bx-message-rounded"></i>
                                {{ $comment->replies()->count() }}
                            </button>
                        </span>

                        {{-- FAVORITE (LIKE UNIFICADO) --}}
                        <livewire:favorite-content :model="$comment" :wire:key="'fav-comment-'.$comment->id" />
                    </div>
                </div>

                {{-- RESPONDER --}}
                @if ($replyingToId === $comment->id)
                    <div class="create-post-box relative">
                        <div class="create-post-top">
                            <img src="{{ asset(Auth::user()->avatar) }}" class="create-avatar">
                            <textarea wire:model.defer="replyContent" class="create-textarea" placeholder="Escribe una respuesta…"></textarea>
                        </div>

                        @include('livewire.posts.media', [
                            'media' => $replyMedia,
                            'removable' => true,
                            'removeMethod' => 'removeReplyMedia',
                        ])

                        <div class="create-actions">
                            <div class="left-actions">
                                <label for="replyMediaInput-{{ $comment->id }}">
                                    <i class="bx bx-image"></i>
                                </label>
                                <input type="file" id="replyMediaInput-{{ $comment->id }}"
                                    wire:model="newReplyMedia" multiple hidden>
                            </div>

                            <button wire:click="addReply" class="btn-post">
                                Responder
                            </button>
                        </div>
                    </div>
                @endif

                {{-- VER RESPUESTAS --}}
                @if ($comment->replies()->count() > 0 && !isset($repliesToShow[$comment->id]))
                    <button wire:click="loadMoreReplies({{ $comment->id }})"
                        class="text-sm text-gray-500 mt-2 cursor-pointer">
                        Mostrar respuestas
                    </button>
                @endif

                {{-- RESPUESTAS --}}
                @if (isset($repliesToShow[$comment->id]))
                    @php
                        $shownReplies = $repliesToShow[$comment->id];
                        $totalReplies = $comment->replies()->count();
                    @endphp

                    @foreach ($comment->replies()->latest()->take($repliesToShow[$comment->id])->get() as $reply)
                        <article class="posts" wire:key="reply-{{ $reply->id }}">
                            <div class="wrap-profile">
                                <a href="{{ route('user.profile', $reply->user->username) }}" class="profile-link">
                                    <img src="{{ asset($reply->user->avatar) }}" class="img-profile">

                                    <div class="profile-name">
                                        <p>{{ $reply->user->name }}</p>
                                        <span>{{ '@' . $reply->user->username }}</span>
                                    </div>
                                </a>

                                <div class="right-content">
                                    <span>{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <p class="text-main-content">{{ $reply->content }}</p>

                            @if ($reply->media)
                                @include('livewire.posts.media', [
                                    'media' => $reply->media,
                                    'removable' => false,
                                ])
                            @endif
                        </article>
                    @endforeach

                    {{-- MOSTRAR MÁS COMENTARIOS --}}
                    @if (isset($repliesToShow[$comment->id]))
                        @if ($repliesToShow[$comment->id] < $comment->replies()->count())
                            <button wire:click="loadMoreReplies({{ $comment->id }})"
                                class="text-sm text-gray-400 cursor-pointer">
                                Mostrar más
                            </button>
                        @endif

                        <button wire:click="loadLessReplies({{ $comment->id }})"
                            class="text-sm text-gray-400 cursor-pointer">
                            Mostrar menos
                        </button>
                    @endif
                @endif
            </article>
        @empty
            <p class="text-gray-500">
                Sé el primero en comentar este post.
            </p>
        @endforelse
    </div>
</div>
