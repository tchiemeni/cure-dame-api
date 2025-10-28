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
        $admin = User::where('email', 'admin@cure.com')->first();

        // Vérifier si l'administrateur existe
        if ($admin) {
            // Créer 5 posts de type 'vidéo' par l'Admin
            Post::factory()->create([
                'user_id' => $admin->id,
                'type' => 'video',
                'media_url' => 'https://example.com/video/' . \Illuminate\Support\Str::uuid() . '.mp4',
            ]);

            // Créer 5 posts de type 'audio' par l'Admin
            Post::factory()->create([
                'user_id' => $admin->id,
                'type' => 'audio',
                'media_url' => 'https://example.com/audio/' . \Illuminate\Support\Str::uuid() . '.mp3',
            ]);

            // Créer 10 posts de type 'prayer' (prière) par l'Admin
            foreach (range(1, 10) as $index) {
                Post::factory()->create([
                    'user_id' => $admin->id,
                    'type' => 'prayer',
                    'media_url' => null, // Pas de média pour les prières simples
                    'content' => 'Contenu de prière généré pour le post ' . ($index + 1), // Contenu statique
                ]);
            }
        }
    }
}
