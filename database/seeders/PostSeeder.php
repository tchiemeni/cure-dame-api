<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer l'ID de l'Administrateur
        $adminId = User::where('email', 'admin@cure.com')->first()->id;

        // Récupérer un ensemble d'IDs d'utilisateurs
        $userIds = User::pluck('id');

        // 1. Créer 5 posts de type 'vidéo' par l'Admin
        Post::factory(5)->create([
            'user_id' => $adminId,
            'type' => 'video',
            'media_url' => 'https://example.com/video/' . fake()->uuid() . '.mp4',
        ]);

        // 2. Créer 5 posts de type 'audio' par l'Admin
        Post::factory(5)->create([
            'user_id' => $adminId,
            'type' => 'audio',
            'media_url' => 'https://example.com/audio/' . fake()->uuid() . '.mp3',
        ]);

        // 3. Créer 10 posts de type 'prayer' (prière) par des utilisateurs aléatoires
        Post::factory(10)->create([
            'user_id' => fake()->randomElement($userIds),
            'type' => 'prayer',
            'media_url' => null, // Pas de média pour les prières simples
            'content' => fake()->paragraph(5),
        ]);
    }
}
