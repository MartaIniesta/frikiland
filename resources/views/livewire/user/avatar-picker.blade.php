<div class="avatar-picker">
    <div class="wrap-avatar" style="display:flex;gap:15px;">
        @if ($selectedAvatar)
            <div class="preview-avatar">
                <img src="{{ in_array($selectedAvatar, $avatars) ? asset($selectedAvatar) : asset('storage/' . $selectedAvatar) }}"
                    alt="Avatar preview">
            </div>
        @endif

        @foreach ($avatars as $avatar)
            <img src="{{ asset($avatar) }}" wire:click="selectAvatar('{{ $avatar }}')"
                style="
                    border: {{ $selectedAvatar === $avatar ? '3px solid #22c55e' : '2px solid transparent' }};
                ">
        @endforeach
    </div>

    <div class="drag-drop" x-data="{ isDragging: false }" @click="$refs.fileInput.click()" @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="
        isDragging = false;
        $refs.fileInput.files = $event.dataTransfer.files;
        $refs.fileInput.dispatchEvent(new Event('change'));
    "
        :style="isDragging ? 'border-color:#22c55e;background:#ecfdf5;' : ''">
        <span>
            Subir imagen
        </span>

        <input type="file" x-ref="fileInput" wire:model="uploadedAvatar" accept="image/*" style="display:none">
    </div>

    @error('uploadedAvatar')
        <p style="color:red;margin-top:5px;">{{ $message }}</p>
    @enderror

    <div class="input-box">
        <button class="btn" wire:click="saveAvatar" @disabled($selectedAvatar === $user->avatar)>
            Save Avatar
        </button>
    </div>

    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition class="success-message"
            style="margin-top:10px;color:#22c55e;">
            {{ session('success') }}
        </div>
    @endif
</div>
