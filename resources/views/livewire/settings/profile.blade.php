<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $username = '';
    public string $bio = '';
    public string $avatar = '';

    public int $followersCount = 0;
    public int $postsCount = 0;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username ?? '';
        $this->bio = $user->bio ?? '';
        $this->avatar = $user->avatar ?? 'images/default-avatar.png';

        $this->followersCount = $user->followers()->count();
        $this->postsCount = $user->posts()->count();
    }

    /**
     * Update the profile information.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->redirect(route('profile.edit'));
    }

    /**
     * Resend verification email.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
};
?>

<section class="w-full">
    <x-header />

    <x-banner-categories>
        <a class="cat active" href="{{ route('profile.edit') }}" wire:navigate>
            {{ __('EDIT PROFILE') }}
        </a>

        <a class="cat" href="{{ route('user-password.edit') }}" wire:navigate>
            {{ __('PASSWORD') }}
        </a>

        <a class="cat" href="{{ route('profile.configuration') }}" wire:navigate>
            {{ __('CONFIGURATION') }}
        </a>
    </x-banner-categories>

    <x-profile.user-card :avatar="$this->avatar" :name="$this->name" :username="$this->username" :bio="$this->bio" :followers="$this->followersCount"
        :posts="$this->postsCount">
        <x-slot:actions>
            <livewire:settings.delete-user-form />
        </x-slot:actions>
    </x-profile.user-card>


    <form wire:submit="updateProfileInformation" class="form-edit-profile">
        <div class="wrap-form-edit-profile">
            <div class="content-left">
                <livewire:user.avatar-picker />
            </div>

            <div class="content-rigth">
                <x-input.profile-input label="Name" model="name" required />

                <x-input.profile-input label="Username" model="username" required />

                <x-input.profile-input label="Biography" model="bio"
                    placeholder="Tell something about yourself..." />

                <x-input.profile-input label="Email" model="email" type="email" required />

                {{-- Email verification --}}
                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <small>
                        Your email address is unverified.
                        <a href="#" wire:click.prevent="resendVerificationNotification">
                            Re-send verification email
                        </a>
                    </small>

                    @if (session('status') === 'verification-link-sent')
                        <small style="color:green;">
                            Verification link sent.
                        </small>
                    @endif
                @endif

                <div class="input-box">
                    <button type="submit" class="btn">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>
