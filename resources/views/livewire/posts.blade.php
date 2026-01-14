<main class="wrap-main">
    <section class="main-content">

        {{-- CREAR POST --}}
        <article class="create-post-box"
            x-data="{ dragging: false }"
            x-on:dragover.prevent="dragging = true"
            x-on:dragleave.prevent="dragging = false"
            x-on:drop.prevent="
                dragging = false;
                $wire.uploadMultiple($event.dataTransfer.files, 'media')
            "
            :class="{ 'dragging': dragging }"
        >
            <div class="create-post-top">
                <img src="/assets/Categorias-Home/CatAccesoriosC.jpg" alt="Perfil" class="create-avatar">

                <textarea
                    wire:model.defer="content"
                    class="create-textarea"
                    placeholder="¿Qué estás pensando?"
                ></textarea>
            </div>

            <div class="create-actions">
                <div class="left-actions">
                    <label for="mediaInput">
                        <i class="bx bx-image"></i>
                    </label>
                    <i class="bx bx-smile"></i>
                    <i class="bx bx-paperclip"></i>

                    <input
                        type="file"
                        id="mediaInput"
                        wire:model="media"
                        multiple
                        hidden
                    >
                </div>

                <button wire:click="addPost" class="btn-post">
                    Publicar
                </button>
            </div>

            {{-- PREVIEWS --}}
            @if ($media)
                @include('livewire.media', ['media' => $media, 'removable' => false])
            @endif
        </article>

        {{-- POSTS --}}
        @foreach ($posts as $post)
            <article class="posts">

                <div class="wrap-profile">
                    <a href="#" class="profile-link">
                        <img src="/assets/Categorias-Home/CatAccesoriosC.jpg" class="img-profile" alt="">
                        <div class="profile-name">
                            <p>{{ $post->user->name }}</p>
                            <span>@{{ $post->user->username ?? 'user' }}</span>
                        </div>
                    </a>

                    <div class="right-content">
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <p class="text-main-content">
                    {{ $post->content }}
                </p>

                {{-- MEDIA --}}
                @if ($post->media)
                    <div class="content-img">
                        @include('livewire.media', [
                            'media' => $post->media,
                            'removable' => $editingPostId === $post->id,
                            'postId' => $post->id,
                        ])
                    </div>
                @endif

                {{-- ICONOS --}}
                <div class="content-icons">
                    <div class="content-icons-left">

                        <span wire:click="replyTo({{ $post->id }})">
                            <i class="bx bx-message-rounded transform-i"></i>
                            {{ $post->replies->count() }}
                        </span>

                        <span class="like-btn">
                            <i class="bx bx-heart"></i>
                            <span class="like-count">20</span>
                        </span>

                        <span class="share-btn">
                            <i class="bx bx-share"></i>
                            <span class="share-count">2</span>
                        </span>

                    </div>

                    <div class="content-icons-right">
                        <i class="bx bx-share"></i>
                    </div>
                </div>

                {{-- RESPONDER --}}
                @if ($replyingToId === $post->id)
                    <div class="reply-card">
                        <textarea
                            wire:model="replyContent"
                            class="create-textarea"
                            placeholder="Escribe tu respuesta..."
                        ></textarea>

                        <input type="file" wire:model="replyMedia" multiple>

                        <div class="create-actions">
                            <button wire:click="addReply" class="btn-post">
                                Responder
                            </button>
                            <button wire:click="cancelReply" class="btn-post cancel">
                                Cancelar
                            </button>
                        </div>
                    </div>
                @endif

                {{-- EDITAR --}}
                @if ($editingPostId === $post->id)
                    <textarea wire:model="editingContent" class="create-textarea"></textarea>
                    <input type="file" wire:model="newMedia" multiple>

                    @php
                        $allMedia = array_merge($existingMedia ?? [], $newMedia ?? []);
                    @endphp

                    @if ($allMedia)
                        @include('livewire.media', [
                            'media' => $allMedia,
                            'removable' => true,
                            'postId' => $post->id,
                        ])
                    @endif

                    <div class="create-actions">
                        <button wire:click="update" class="btn-post">Guardar</button>
                        <button wire:click="cancelEdit" class="btn-post cancel">Cancelar</button>
                    </div>
                @endif

                {{-- RESPUESTAS --}}
                @include('livewire.replies', ['replies' => $post->replies])

            </article>
        @endforeach

    </section>
</main>
