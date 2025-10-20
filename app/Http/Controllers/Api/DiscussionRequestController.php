<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscussionRequest;
use App\Notifications\RequestTakenAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DiscussionRequestController extends Controller
{
    /**
     * Crée une nouvelle requête de discussion.
     * ROUTE: POST /api/discussion-requests (Protégée par auth:sanctum)
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. Création de la requête
        $requestData = [
            'user_id' => $request->user()->id, // L'ID de l'utilisateur connecté
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending', // Statut par défaut
        ];

        $discussionRequest = DiscussionRequest::create($requestData);

        return response()->json([
            'message' => 'Votre demande de discussion a été soumise. Un homme de Dieu vous contactera bientôt.',
            'request' => $discussionRequest
        ], 201);
    }

    /**
     * Affiche les requêtes soumises par l'utilisateur connecté.
     * ROUTE: GET /api/discussion-requests/me (Protégée)
     */
    public function userRequests(Request $request)
    {
        $requests = DiscussionRequest::where('user_id', $request->user()->id)
                                     ->latest()
                                     ->get();

        return response()->json($requests);
    }

    // --- Méthodes pour l'administration (optionnelles mais cruciales) ---

    // Pour gérer ces requêtes, vous auriez besoin d'une validation isAdmin

    /**
     * Affiche toutes les requêtes (pour l'administration).
     * ROUTE: GET /api/admin/discussion-requests (Nécessite une route et un middleware Admin)
     */
    public function indexAdmin()
    {
       // 🚨 Logique pour charger toutes les requêtes avec la relation 'user' 🚨

    // Le 'where' n'est pas nécessaire ici, car l'admin voit tout.
    $requests = DiscussionRequest::with('user')
                ->latest() // Les plus récentes en premier
                ->get();

    // Optionnel: utiliser une Resource pour formater la réponse si nécessaire
    return response()->json($requests);
    }

    /**
     * Met à jour le statut d'une requête (pour l'administration).
     * ROUTE: PUT/PATCH /api/discussion-requests/{discussionRequest}
     */
    public function update(Request $request, DiscussionRequest $discussionRequest)
    {
        // Dans un cas réel, vérifiez is_admin ici!

        $request->validate([
            'status' => ['required', Rule::in(['pending', 'in_progress', 'resolved'])],
        ]);

        $discussionRequest->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Statut de la requête mis à jour.',
            'request' => $discussionRequest
        ]);
    }

   public function takeAction(DiscussionRequest $request)
{
    // 🚨 1. VÉRIFICATION DU STATUT DE LA REQUÊTE 🚨
    // Si la requête n'est pas "pending", on ne fait rien et on envoie un statut d'échec
    if ($request->status !== 'pending') {
        return response()->json([
            'message' => 'Cette requête a déjà été prise en charge ou résolue.'
        ], 400); // 400 Bad Request est approprié ici.
    }

    // L'utilisateur authentifié (l'Admin) est déjà vérifié par le middleware 'admin' sur la route.
    // La vérification $request->user()->isAdmin() est redondante ici, car vous êtes
    // dans une route sécurisée par un middleware d'administration.

    // 2. Mise à jour du statut
    $request->status = 'in_progress';
    $request->save();

    try {
        // 🚨 3. ENVOI SÉCURISÉ DE LA NOTIFICATION 🚨
        // On utilise la relation ($request->user) pour accéder à l'utilisateur lié
        // Assurez-vous que l'utilisateur est chargé via Route Model Binding ou with('user')
        $request->user->notify(new RequestTakenAction($request));
    } catch (\Exception $e) {
        // En cas d'échec de l'envoi de courriel, on peut logger l'erreur
        // mais on retourne quand même un succès pour l'action BDD
        Log::error("Erreur lors de l'envoi de la notification pour Request ID {$request->id}: " . $e->getMessage());
        // Vous pouvez choisir de relancer l'exception si l'échec de l'email est critique
    }

    // Retourne la ressource mise à jour
    // Le front-end a besoin de l'objet mis à jour pour rafraîchir la liste
    return response()->json($request);
}
}
