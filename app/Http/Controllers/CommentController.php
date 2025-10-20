<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post; // Import du modèle Post
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Affiche les commentaires d'un post spécifique.
     */
    public function index(Post $post)
    {
        // Charge les commentaires pour le post donné, avec l'utilisateur
        return response()->json($post->comments()->with('user')->latest()->get());
    }

    /**
     * Enregistre un nouveau commentaire pour un post.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id, // Utilisateur authentifié
            'content' => $request->content,
        ]);

        // Retourne le nouveau commentaire avec son utilisateur pour l'affichage immédiat
        return response()->json($comment->load('user'), 201);
    }

    // ... Les méthodes show, update, destroy peuvent être ajoutées plus tard.
}
