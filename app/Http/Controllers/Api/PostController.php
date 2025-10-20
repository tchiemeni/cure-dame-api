<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Affiche une liste de tous les posts (Le "Fil" public).
     * ROUTE: GET /api/posts
     */
    public function index()
    {
        // On récupère tous les posts triés par le plus récent
        $posts = Post::with('user:id,name')->latest()->paginate(15);

        return response()->json($posts);
    }

    /**
     * Crée un nouveau post.
     * ROUTE: POST /api/posts (Protégée par auth:sanctum)
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => ['required', Rule::in(['video', 'audio', 'prayer'])],
            // 'file' est le champ pour le fichier média (vidéo/audio/image de prière)
            'file' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png|max:102400', // max 100MB
        ]);

        $mediaUrl = null;

        // 2. Gestion du téléversement de fichier (pour video/audio)
        if ($request->hasFile('file')) {
            // Dans un vrai projet, utilisez Storage::disk('s3')->put...
            $path = $request->file('file')->store('posts/media', 'public');
            $mediaUrl = Storage::url($path);
        } elseif ($request->type !== 'prayer') {
            // Si ce n'est pas une prière (texte), un fichier média est souvent requis.
            // On peut adapter cette validation selon vos besoins exacts.
        }

        // 3. Création du post
        $post = Post::create([
           'user_id' => $request->user()->id,  // L'ID de l'utilisateur connecté
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'media_url' => $mediaUrl,
        ]);

        return response()->json([
            'message' => 'Contenu publié avec succès.',
            'post' => $post
        ], 201);
    }

    /**
     * Affiche un post spécifique.
     * ROUTE: GET /api/posts/{post}
     */
    public function show(Post $post)
    {
        // Ajout de l'utilisateur associé
        $post->load('user:id,name');

        return response()->json($post);
    }

    /**
     * Met à jour un post existant.
     * ROUTE: PUT/PATCH /api/posts/{post} (Protégée)
     */
    public function update(Request $request, Post $post)
    {
        // Vérification de l'autorisation : seul l'auteur peut modifier
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé à modifier ce contenu.'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => ['required', Rule::in(['video', 'audio', 'prayer'])],
            // La logique de mise à jour du fichier est plus complexe et peut être simplifiée ici.
        ]);

        $post->update($request->only('title', 'content', 'type'));

        return response()->json([
            'message' => 'Contenu mis à jour.',
            'post' => $post
        ]);
    }

    /**
     * Supprime un post.
     * ROUTE: DELETE /api/posts/{post} (Protégée)
     */
    public function destroy(Request $request,Post $post)
    {
        // Vérification de l'autorisation : seul l'auteur peut supprimer
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé à supprimer ce contenu.'], 403);
        }

        // Optionnel : supprimer le fichier média du stockage si media_url existe
        if ($post->media_url) {
            // Exemple : Storage::disk('public')->delete(str_replace('/storage', '', $post->media_url));
        }

        $post->delete();

        return response()->json(['message' => 'Contenu supprimé avec succès.']);
    }

    public function share(Post $post)
    {
    // Incrémente le compteur de partages
    $post->increment('share_count');

    // Retourne le nouveau compteur pour confirmation (optionnel)
    return response()->json([
        'message' => 'Share count incremented.',
        'share_count' => $post->share_count
    ]);
    }
}
