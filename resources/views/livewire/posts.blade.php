<div class="posts-container" x-data="{ dragging: false }">

    {{-- Crear post raíz --}}
    <div x-on:dragover.prevent="dragging = true"
         x-on:dragleave.prevent="dragging = false"
         x-on:drop.prevent="dragging = false"
         x-on:drop.prevent="$wire.uploadMultiple($event.dataTransfer.files, 'media')"
         :class="{'post-root': true, 'dragging': dragging}">

        <textarea wire:model="content" class="post-textarea" placeholder="Escribe algo..."></textarea>
        <input type="file" id="mediaInput" wire:model="media" multiple class="hidden">
        <label for="mediaInput">
            <img src="{{ asset('imagen.png') }}" alt="Multimedia" class="multimedia">
        </label>

        <button wire:click="addPost" class="post-button">Publicar</button>

        {{-- Previews --}}
        @if ($media)
            @include('livewire.media', ['media' => $media, 'removable' => false])
        @endif
    </div>

    <hr>

    {{-- Posts --}}
    @foreach ($posts as $post)
        <div class="post-card">
            <strong>{{ $post->user->name }}</strong>
            <p>{{ $post->content }}</p>

            {{-- Media del post --}}
            @if ($post->media)
                @include('livewire.media', ['media' => $post->media, 'removable' => $editingPostId === $post->id, 'postId' => $post->id])
            @endif

            {{-- Botones --}}
            <div class="post-buttons">
                <button wire:click="replyTo({{ $post->id }})" class="post-button">Responder</button>

                @if(Auth::id() === $post->user_id)
                    <button wire:click="edit({{ $post->id }})" style="color: green;">Editar</button>
                    <button wire:click="delete({{ $post->id }})" style="color: red;">Eliminar</button>
                @endif
            </div>

            {{-- Caja para responder --}}
            @if ($replyingToId === $post->id)
                <div class="reply-card">
                    <textarea wire:model="replyContent" class="post-textarea" placeholder="Escribe tu respuesta..."></textarea>
                    <input type="file" wire:model="replyMedia" multiple class="post-input-file">
                    <div class="post-buttons">
                        <button wire:click="addReply" class="post-button">Responder</button>
                        <button wire:click="cancelReply" class="post-button cancel">Cancelar</button>
                    </div>
                </div>
            @endif

            {{-- Editar post --}}
            @if ($editingPostId === $post->id)
                <textarea wire:model="editingContent" class="post-textarea"></textarea>
                <input type="file" wire:model="newMedia" multiple class="post-input-file">
                
                @php
                    $allMedia = array_merge($existingMedia ?? [], $newMedia ?? []);
                @endphp
                @if ($allMedia)
                    @include('livewire.media', ['media' => $allMedia, 'removable' => true, 'postId' => $post->id])
                @endif

                <button wire:click="update" class="post-button">Guardar</button>
                <button wire:click="cancelEdit" class="post-button cancel">Cancelar</button>
            @endif

            {{-- Respuestas --}}
            @include('livewire.replies', ['replies' => $post->replies])
        </div>
    @endforeach
</div>
