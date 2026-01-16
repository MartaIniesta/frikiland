@props([
    'type' => 'text',
    'name',
    'placeholder' => '',
    'icon' => '',
    'value' => '',
    'required' => false,
    'autofocus' => false,
    'form' => null,
])

<div class="input-box">
    <div class="input-wrapper">
        @if ($icon)
            <i class="bx {{ $icon }}"></i>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            @if ($attributes->has('wire:model'))
                wire:model.defer="{{ $attributes->get('wire:model') }}"
            @endif
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
        >

        @if ($type === 'password')
            <button type="button" class="show-password" onclick="togglePassword(this)">
                <i class="bx bx-hide"></i>
            </button>
        @endif
    </div>

    {{-- Errores --}}
    @if ($form)
        @if (old('form') === $form)
            @error($name)
                <small class="error-text">{{ $message }}</small>
            @enderror
        @endif
    @else
        @error($name)
            <small class="error-text">{{ $message }}</small>
        @enderror
    @endif
</div>
