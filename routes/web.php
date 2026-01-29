<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Livewire\Pages\{SocialWeb, ShopWeb};
use App\Livewire\Posts\ShowPost;
use App\Livewire\User\{ProfileUser, ContentPrivacy};
use App\Livewire\Notifications\NotificationsIndex;

Route::get('/', fn() => view('home'))
    ->name('home');

Route::get('/shop-web', ShopWeb::class)
    ->name('shop-web');

Route::redirect('/social-web', '/social-web/for-you')
    ->name('social-web');

Route::middleware('auth')->group(function () {});

Route::get('/register', fn() => redirect()->route('login'))
    ->name('register');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    /* PAGES */
    Route::get('/social-web/for-you', SocialWeb::class)
        ->name('social-web.for-you');

    Route::get('/social-web/following', SocialWeb::class)
        ->name('social-web.following');

    Route::get('/posts/{post}', ShowPost::class)
        ->name('posts.show');

    Route::get('/user/{username}', ProfileUser::class)
        ->name('user.profile');

    Route::get('/profile/configuration', ContentPrivacy::class)
        ->name('profile.configuration');

    Route::get('/notifications', NotificationsIndex::class)
        ->name('notifications.index');


    /* SETTINGS */
    Route::redirect('/settings', '/settings/profile');

    Volt::route('/settings/profile', 'settings.profile')
        ->name('profile.edit');

    Volt::route('/settings/password', 'settings.password')
        ->name('user-password.edit');

    Volt::route('/settings/appearance', 'settings.appearance')
        ->name('appearance.edit');

    Volt::route('/settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(
                        Features::twoFactorAuthentication(),
                        'confirmPassword'
                    ),
                ['password.confirm'],
                []
            )
        )
        ->name('two-factor.show');
});
