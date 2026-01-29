@auth
    <div class="mb-6">
        <article class="create-post-box relative" x-data="{
            dragging: false,
            showLinkInput: false,
            link: '',
            handlePaste(event) {
                const text = (event.clipboardData || window.clipboardData).getData('text');
                const urlRegex = /(https?:\/\/[^\s]+)/g;

                if (urlRegex.test(text)) {
                    console.log('Enlace detectado:', text);
                }
            },
            addLink() {
                if (!this.link) return;

                this.$refs.textarea.value += ' ' + this.link;
                this.link = '';
                this.showLinkInput = false;

                this.$refs.textarea.dispatchEvent(new Event('input'));
            }
        }" x-on:dragover.prevent="dragging = true"
            x-on:dragleave.prevent="dragging = false"
            x-on:drop.prevent="
                dragging = false;
                $wire.uploadMultiple('newMedia', [...$event.dataTransfer.files])
            "
            :class="{ 'dragging': dragging }">

            {{-- CABECERA --}}
            <div class="create-post-top">
                <img src="{{ asset(Auth::user()->avatar) }}" class="create-avatar">

                <textarea x-ref="textarea" wire:model.defer="content" class="create-textarea" placeholder="Escribe un comentario…"
                    @paste="handlePaste($event)"></textarea>
            </div>

            {{-- PREVIEW MEDIA --}}
            @include('livewire.posts.media', [
                'media' => $media,
                'removable' => true,
                'removeMethod' => 'removeMedia',
            ])

            {{-- INPUT ENLACE --}}
            <div x-show="showLinkInput" x-transition class="link-input">
                <input type="url" class="link-input-field" placeholder="Pega un enlace (https://...)" x-model="link">

                <button type="button" class="link-input-btn" @click="addLink">
                    Añadir enlace
                </button>
            </div>

            {{-- ACCIONES --}}
            <div class="create-actions">
                <div class="left-actions">
                    <label for="commentMediaInput">
                        <i class="bx bx-image"></i>
                    </label>

                    <input type="file" id="commentMediaInput" wire:model="newMedia" multiple hidden>

                    <button type="button" @click="showLinkInput = !showLinkInput" title="Añadir enlace">
                        <i class="bx bx-paperclip"></i>
                    </button>
                </div>

                <button wire:click="addComment" class="btn-post">
                    Responder
                </button>
            </div>
        </article>
    </div>
@endauth
