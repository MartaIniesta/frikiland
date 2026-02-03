<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Models\ContentPrivacy;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'bio',
    ];

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

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // Posts del usuario
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Posts favoritos
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Usuarios que sigo
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'follower_id',
            'followed_id'
        )->withTimestamps();
    }

    // Usuarios que me siguen
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'followed_id',
            'follower_id'
        )->withTimestamps();
    }


    // Privacidad
    public function contentPrivacies()
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


    // Posts compartidos
    public function shares()
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


    // Chat
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
