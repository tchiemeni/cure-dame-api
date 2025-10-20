<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'is_admin' => 'boolean', // 🚨 AJOUTEZ CETTE LIGNE 🚨
        ];
    }

    // --- Relations ---

    /**
     * Un utilisateur peut avoir plusieurs posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Un utilisateur peut faire plusieurs requêtes de discussion.
     */
    public function discussionRequests(): HasMany
    {
        return $this->hasMany(DiscussionRequest::class);
    }
 protected $appends = ['role']; // 🚨 ADD THIS LINE 🚨

    protected function role(): Attribute
   {
    return Attribute::make(
        get: fn (mixed $value, array $attributes) =>
            // 🚨 Correction : Utiliser isset() pour vérifier si la clé existe 🚨
            isset($attributes['is_admin']) && $attributes['is_admin'] ? 'admin' : 'user',
    );
   }
    public function isAdmin(): bool
    {
        // 🚨 MÉTHODE À AJOUTER 🚨

        // Option A: Si tu as une colonne 'is_admin' (booléen)
         return $this->is_admin;

        // Option B: Si tu as une colonne 'role' (string)
        //return $this->role === 'admin' || $this->role === 'homme_de_dieu';
    }
}
