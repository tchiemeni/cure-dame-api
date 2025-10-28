<?php

// config/cors.php

return [

    /*
     * Les chemins qui devraient être soumis au middleware CORS.
     * Inclure 'api/*' est suffisant pour les routes d'API.
     */
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', 'register'],

    /*
     * Les origines autorisées à accéder à la ressource.
     * C'EST ICI que vous ajoutez votre frontend Angular.
     */
    'allowed_origins' => [
        'https://cure-dame-2025.netlify.app'
    ],

    /*
     * Les modèles d'origines autorisées.
     * Laissez vide si vous utilisez 'allowed_origins' ci-dessus.
     */
    'allowed_origins_patterns' => [],

    /*
     * Les méthodes (GET, POST, etc.) autorisées. '*' signifie toutes.
     */
    'allowed_methods' => ['*'],

    /*
     * Les en-têtes autorisés. '*' signifie tous (y compris 'Authorization').
     */
    'allowed_headers' => ['*'],

    /*
     * Les en-têtes qui peuvent être exposés au navigateur.
     */
    'exposed_headers' => [],

    /*
     * Temps pendant lequel le pré-vol est mis en cache (en secondes).
     */
    'max_age' => 0,

    /*
     * Indique si les cookies ou les jetons d'authentification doivent être inclus.
     * Doit être true pour l'authentification.
     */
    'supports_credentials' => true,

];
