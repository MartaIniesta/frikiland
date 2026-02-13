<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password', 'username', 'avatar', 'bio'];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::created(function ($user) {
            if (! $user->hasAnyRole()) {
                $user->assignRole('user');
            }
        });
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'follower_id',
            'followed_id'
        )->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'followed_id',
            'follower_id'
        )->withTimestamps();
    }

    public function contentPrivacies(): HasMany
    {
        return $this->hasMany(ContentPrivacy::class);
    }

    public function privacyFor(string $type): string
    {
        return $this->contentPrivacies
            ->where('type', $type)
            ->first()
            ?->visibility ?? 'public';
    }

    protected function canViewContent(string $type, ?User $viewer = null): bool
    {
        $visibility = $this->privacyFor($type);

        if ($visibility === 'public') {
            return $viewer !== null;
        }

        if (! $viewer) {
            return false;
        }

        if ($visibility === 'private') {
            return $viewer->id === $this->id;
        }

        if ($visibility === 'followers') {
            return $viewer->id === $this->id
                || $this->followers()
                ->where('follower_id', $viewer->id)
                ->exists();
        }

        return false;
    }

    public function canViewFavorites(?User $viewer = null): bool
    {
        return $this->canViewContent('favorites', $viewer);
    }

    public function canViewShared(?User $viewer = null): bool
    {
        return $this->canViewContent('shared', $viewer);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    public function sharedPosts()
    {
        return $this->shares()
            ->where('shareable_type', Post::class)
            ->with('shareable')
            ->get()
            ->pluck('shareable');
    }

    public function sharedComments()
    {
        return $this->shares()
            ->where('shareable_type', PostComment::class)
            ->with('shareable')
            ->get()
            ->pluck('shareable');
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function chatRequestsSent(): HasMany
    {
        return $this->hasMany(ChatRequest::class, 'from_user_id');
    }

    public function chatRequestsReceived(): HasMany
    {
        return $this->hasMany(ChatRequest::class, 'to_user_id');
    }

    public function scopeAvailableForChat(Builder $query, User $authUser): Builder
    {
        return $query->whereIn('id', $authUser->following()->pluck('users.id'))->whereDoesntHave('conversations', function ($q) use ($authUser) {
            $q->whereHas('users', fn($u) => $u->where('users.id', $authUser->id));
        })->whereDoesntHave('chatRequestsReceived', function ($q) use ($authUser) {
            $q->where('from_user_id', $authUser->id);
        })->whereDoesntHave('chatRequestsSent', function ($q) use ($authUser) {
            $q->where('to_user_id', $authUser->id);
        });
    }
}
