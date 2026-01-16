@props([
    'label' => null,
    'model' => null,
    'type' => 'text',
    'required' => false,
    'placeholder' => null,
])

@php
    $wireModel = $attributes->wire('model')->value();
    $finalModel = $wireModel ?? $model;
@endphp

<div class="input-box">
    @if($label)
        <label for="{{ $finalModel }}">
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $finalModel }}"
        name="{{ $finalModel }}"
        type="{{ $type }}"
        @if($wireModel)
            wire:model.defer="{{ $wireModel }}"
        @elseif($model)
            wire:model.defer="{{ $model }}"
        @endif
        {{ $attributes->except('wire:model') }}
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
    >

    @error($finalModel)
        <small class="error-text">{{ $message }}</small>
    @enderror
</div>
