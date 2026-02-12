<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Pages\{SocialWeb, ShopWeb};
use App\Livewire\Posts\{ShowPost, HashtagShow};
use App\Livewire\User\{ProfileUser, ContentPrivacy};
use App\Livewire\Notifications\NotificationsIndex;
use App\Livewire\Chat\{ChatIndex, ChatShow};
use App\Livewire\SearchPosts;
use App\Http\Controllers\{ChatStartController, ChatRequestController};

Route::get('/', fn() => view('home'))
    ->name('home');

Route::prefix('shop-web')->group(function () {
    Route::get('/', ShopWeb::class)
        ->name('shop-web');

    Route::get('/cart', ShopWeb::class)
        ->name('shop-web.cart');
});

Route::prefix('payment')->group(function () {
    Route::get('/success', [App\Http\Controllers\PaymentController::class, 'success'])
        ->middleware('auth')
        ->name('payment.success');

    Route::get('/cancel', function () {
        return redirect()
            ->route('shop-web')
            ->with('error', 'Pago cancelado');
    })->name('payment.cancel');
});

Route::prefix('social-web')->group(function () {
    Route::redirect('/', '/social-web/for-you')
        ->name('social-web');

    Route::get('/for-you', SocialWeb::class)
        ->name('social-web.for-you');
});

Route::prefix('search')->group(function () {
    Route::get('/posts', SearchPosts::class)
        ->name('search.posts');
});

Route::prefix('hashtag')->group(function () {
    Route::get('/{name}', HashtagShow::class)
        ->name('hashtag.show');
});

Route::get('/register', fn() => redirect()->route('login'))
    ->name('register');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::prefix('shop-web')->group(function () {
        Route::get('/my-products', ShopWeb::class)
            ->name('shop-web.mine');
    });

    Route::prefix('social-web')->group(function () {
        Route::get('/following', SocialWeb::class)
            ->name('social-web.following');
    });

    Route::prefix('posts')->group(function () {
        Route::get('/{post}', ShowPost::class)
            ->name('posts.show');
    });

    Route::prefix('user')->group(function () {
        Route::get('/{username}', ProfileUser::class)
            ->name('user.profile');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/configuration', ContentPrivacy::class)
            ->name('profile.configuration');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', NotificationsIndex::class)
            ->name('notifications.index');
    });

    Route::prefix('chat')->group(function () {
        Route::get('/', ChatIndex::class)
            ->name('chat.index');

        Route::get('/start/{user}', ChatStartController::class)
            ->name('chat.start');

        Route::get('/{conversation}', ChatShow::class)
            ->name('chat.show');

        Route::post('/requests/{chatRequest}/accept', [ChatRequestController::class, 'accept'])
            ->name('chat-requests.accept');

        Route::post('/requests/{chatRequest}/reject', [ChatRequestController::class, 'reject'])
            ->name('chat-requests.reject');
    });

    Route::prefix('settings')->group(function () {
        Route::redirect('/', '/settings/profile');

        Volt::route('/profile', 'settings.profile')
            ->name('profile.edit');

        Volt::route('/password', 'settings.password')
            ->name('user-password.edit');

        Volt::route('/appearance', 'settings.appearance')
            ->name('appearance.edit');

        Volt::route('/two-factor', 'settings.two-factor')
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
});
