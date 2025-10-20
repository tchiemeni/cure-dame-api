<?php

namespace Database\Seeders;

use App\Models\DiscussionRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscussionRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un ensemble d'IDs d'utilisateurs
        $userIds = User::pluck('id');

        // Créer 5 requêtes en attente
        DiscussionRequest::factory(5)->create([
            'user_id' => fake()->randomElement($userIds),
            'status' => 'pending',
        ]);

        // Créer 3 requêtes en cours de traitement
        DiscussionRequest::factory(3)->create([
            'user_id' => fake()->randomElement($userIds),
            'status' => 'in_progress',
        ]);

        // Créer 2 requêtes résolues
        DiscussionRequest::factory(2)->create([
            'user_id' => fake()->randomElement($userIds),
            'status' => 'resolved',
        ]);
    }
}
