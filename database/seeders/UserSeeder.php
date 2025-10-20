<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Création de l'Administrateur (Homme de Dieu)
        User::create([
            'name' => 'Homme de Dieu Admin',
            'email' => 'admin@cure.com',
            'password' => Hash::make('password'), // Mot de passe : password
            'is_admin' => true, // Assurez-vous que cette colonne existe
            // Si vous utilisez une colonne 'role', ajoutez-la ici
        ]);

        // 2. Création d'un utilisateur régulier pour les tests
        User::create([
            'name' => 'Utilisateur Test',
            'email' => 'user@cure.com',
            'password' => Hash::make('password'), // Mot de passe : password
            'is_admin' => false,
        ]);

        // 3. Création de 10 utilisateurs supplémentaires via la Factory
        User::factory(10)->create();
    }
}
