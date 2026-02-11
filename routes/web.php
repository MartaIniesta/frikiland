<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Pages\{SocialWeb, ShopWeb};
use App\Livewire\Posts\ShowPost;
use App\Livewire\User\{ProfileUser, ContentPrivacy};
use App\Livewire\Notifications\NotificationsIndex;
use App\Livewire\Chat\ChatIndex;
use App\Livewire\Chat\ChatShow;
use App\Livewire\SearchPosts;
use App\Http\Controllers\ChatStartController;
use App\Http\Controllers\ChatRequestController;
use \App\Livewire\Posts\HashtagShow;


Route::get('/', fn() => view('home'))
    ->name('home');

Route::get('/shop-web', ShopWeb::class)
    ->name('shop-web');

Route::get('/shop-web/cart', ShopWeb::class)
    ->name('shop-web.cart');

Route::get('/payment/success', [App\Http\Controllers\PaymentController::class, 'success'])
    ->name('payment.success')
    ->middleware('auth');

Route::get('/payment/cancel', function () {
    return redirect()->route('shop-web')->with('error', 'Pago cancelado');
})->name('payment.cancel');

Route::redirect('/social-web', '/social-web/for-you')
    ->name('social-web');

Route::get('/social-web/for-you', SocialWeb::class)
    ->name('social-web.for-you');

Route::get('/search/posts', SearchPosts::class)
    ->name('search.posts');


Route::middleware('auth')->group(function () {});

Route::get('/register', fn() => redirect()->route('login'))
    ->name('register');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    /* SHOP */
    Route::get('/shop-web/my-products', ShopWeb::class)
        ->name('shop-web.mine');

    /* PAGES */
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

    Route::get('/hashtag/{name}', HashtagShow::class)
        ->name('hashtag.show');

    /* CHAT */
    Route::get('/chat', ChatIndex::class)
        ->name('chat.index');

    Route::get('/chat/start/{user}', ChatStartController::class)
        ->name('chat.start');

    Route::get('/chat/{conversation}', ChatShow::class)
        ->name('chat.show');

    Route::post('/chat-requests/{chatRequest}/accept', [ChatRequestController::class, 'accept'])
        ->name('chat-requests.accept');

    Route::post('/chat-requests/{chatRequest}/reject', [ChatRequestController::class, 'reject'])
        ->name('chat-requests.reject');


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
