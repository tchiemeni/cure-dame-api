<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Enregistrement d'un nouvel utilisateur.
     * ROUTE: POST /api/register
     */
    public function register(Request $request)
    {
        try {
            // 1. Validation des données
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed', // 'confirmed' vérifie password_confirmation
            ]);

            // 2. Création de l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 3. Création du token d'accès (authentification immédiate)
            $token = $user->createToken("auth_token")->plainTextToken;

            // 4. Réponse
            return response()->json([
                'message' => 'Utilisateur créé avec succès.',
                'user' => $user->only('id', 'name', 'email'), // Retourne seulement les champs sûrs
                'token' => $token,
            ], 201); // 201 Created

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue lors de l\'enregistrement.'], 500);
        }
    }

    /**
     * Connexion de l'utilisateur.
     * ROUTE: POST /api/login
     */
    public function login(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Tentative d'authentification
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Identifiants invalides.'
            ], 401); // 401 Unauthorized
        }

        // 3. Récupération de l'utilisateur
       $user = Auth::user();
//   Log::info('Rôle renvoyé à Angular:', ['role' => $user->role]);
        // 4. Création du token d'accès
        $token = $user->createToken("auth_token")->plainTextToken;

        // 5. Réponse
        return response()->json([
            'message' => 'Connexion réussie.',
            'user' => $user->only('id', 'name', 'email', 'role'),
            'token' => $token,
        ]);
    }

    /**
     * Déconnexion de l'utilisateur (révocation du token).
     * ROUTE: POST /api/logout (protégée)
     */
    public function logout(Request $request)
    {
       // Alternative : Révoque TOUS les jetons émis par Sanctum pour cet utilisateur.
       $request->user()->tokens()->delete();

        return response()->json(['message' => 'Déconnexion réussie. Token révoqué.']);
    }

    /**
     * Récupération de l'utilisateur connecté (pour vérification côté Angular).
     * ROUTE: GET /api/user (protégée) - A ajouter dans routes/api.php si besoin
     */
    public function user(Request $request)
    {
        return response()->json($request->user()->only('id', 'name', 'email'));
    }
}
