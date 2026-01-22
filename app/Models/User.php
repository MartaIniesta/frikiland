<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
// use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\ContentPrivacy;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles; //TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
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
    public function favoritePosts()
    {
        return $this->belongsToMany(Post::class, 'favorites')
            ->withTimestamps();
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

    /**
     * Privacidad de contenidos (favorites, shared, etc.)
     */
    public function contentPrivacies()
    {
        return $this->hasMany(ContentPrivacy::class);
    }

    /**
     * Obtener visibilidad de un tipo de contenido
     */
    public function privacyFor(string $type): string
    {
        return $this->contentPrivacies
            ->where('type', $type)
            ->first()
            ?->visibility ?? 'public';
    }

    public function canViewFavorites(?User $viewer = null): bool
    {
        $visibility = $this->privacyFor('favorites');

        if ($visibility === 'public') {
            return true;
        }

        if (! $viewer) {
            return false;
        }

        if ($visibility === 'private') {
            return $viewer->id === $this->id;
        }

        if ($visibility === 'followers') {
            return $viewer->id === $this->id
                || $this->followers()->where('follower_id', $viewer->id)->exists();
        }

        return false;
    }

    // Posts compartidos
    public function sharedPosts()
    {
        return $this->belongsToMany(Post::class, 'shares')
            ->withTimestamps();
    }
}
