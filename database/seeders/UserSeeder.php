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
        // 1. Vérification et création de l'Administrateur (Homme de Dieu)
        if (!User::where('email', 'admin@cure.com')->exists()) {
            User::create([
                'name' => 'Homme de Dieu Admin',
                'email' => 'admin@cure.com',
                'password' => Hash::make('password'), // Mot de passe : password
                'is_admin' => true,
            ]);
        }

        // 2. Vérification et création d'un utilisateur régulier pour les tests
        // if (!User::where('email', 'user@cure.com')->exists()) {
        //     User::create([
        //         'name' => 'Utilisateur Test',
        //         'email' => 'user@cure.com',
        //         'password' => Hash::make('password'), // Mot de passe : password
        //         'is_admin' => false,
        //     ]);
        // }

        // // 3. Création de 10 utilisateurs supplémentaires via la Factory
        // User::factory(10)->create();
    }
}
