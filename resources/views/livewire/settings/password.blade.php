<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    <x-header />

    <x-banner-categories>
        <a class="cat" href="{{ route('profile.edit') }}" wire:navigate>
            {{ __('EDIT PROFILE') }}
        </a>

        <a class="cat active" href="{{ route('user-password.edit') }}" wire:navigate>
            {{ __('PASSWORD') }}
        </a>
    </x-banner-categories>

    <div class="title-password">
        <h2>~ {{ __('Change your password') }} ~</h2>
    </div>

    <form method="POST" wire:submit="updatePassword" class="form-edit-password">
        <div class="content-form-update-password">
            <x-input.auth-input type="password" name="current_password" placeholder="{{ __('Current password') }}"
                wire:model="current_password" required />

            <x-input.auth-input type="password" name="password" placeholder="{{ __('New password') }}"
                wire:model="password" required />

            <x-input.auth-input type="password" name="password_confirmation" placeholder="{{ __('Confirm Password') }}"
                wire:model="password_confirmation" required />

            <div class="input-box">
                <button type="submit" class="btn" data-test="update-password-button">
                    {{ __('Save') }}
                </button>

                <x-action-message on="password-updated" class="password-updated">
                    {{ __('Password saved successfully') }}
                </x-action-message>
            </div>
        </div>
    </form>
</section>
