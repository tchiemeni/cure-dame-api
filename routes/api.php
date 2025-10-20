<?php



// routes/api.php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\DiscussionRequestController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// --- Routes d'Authentification (Non protégées) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Routes Protégées par Sanctum (Nécessitent un token) ---
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Posts (Lecture pour tous, CRUD pour les admins ou auteurs)
    Route::apiResource('posts', PostController::class); // Gère index, store, show, update, destroy

    // Requêtes de discussion (Lecture/Création pour les utilisateurs, Gestion pour les admins)
    Route::post('discussion-requests', [DiscussionRequestController::class, 'store']); // Créer une requête
    Route::get('discussion-requests/me', [DiscussionRequestController::class, 'userRequests']); // Voir ses propres requêtes

     // 🔑 Routes Admin (A Protéger davantage avec un Middleware 'admin')
    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        Route::get('requests', [DiscussionRequestController::class, 'indexAdmin']);
        Route::patch('discussion-requests/{discussionRequest}', [DiscussionRequestController::class, 'update']);
        //la route doit être ici
    Route::post('/requests/{request}/take-action', [DiscussionRequestController::class, 'takeAction']);
    });

    // API de commentaires
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);



});

// --- Routes publiques (lecture des posts sans être connecté) ---
// Route::get('posts/public', [PostController::class, 'indexPublic']); // Optionnel
