<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Pages\SocialWeb;
use App\Livewire\Pages\ShopWeb;
use App\Livewire\Posts\PostShow;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/shop-web', ShopWeb::class)
    ->name('shop-web');

Route::get('/social-web', SocialWeb::class)
    ->name('social-web');

Route::get('/register', function () {
    return redirect()->route('login');
})->name('register');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/posts/{post}', PostShow::class)
        ->name('posts.show');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')
        ->name('profile.edit');

    Volt::route('settings/password', 'settings.password')
        ->name('user-password.edit');

    Volt::route('settings/appearance', 'settings.appearance')
        ->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(
                        Features::twoFactorAuthentication(),
                        'confirmPassword'
                    ),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
