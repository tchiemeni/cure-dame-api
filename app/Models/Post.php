<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'share_count',
        'media_url',
    ];

    /**
     * Un post appartient à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 🚨 DÉFINITION DE LA RELATION MANQUANTE 🚨
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
