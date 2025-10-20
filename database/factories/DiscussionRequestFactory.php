<?php

namespace Database\Factories;

use App\Models\DiscussionRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscussionRequestFactory extends Factory
{
    protected $model = DiscussionRequest::class;

    public function definition(): array
    {
        // Statuts possibles selon votre base de données
        $statuses = ['pending', 'in_progress', 'resolved'];

        return [
            'subject' => $this->faker->sentence(5),
            'message' => $this->faker->paragraph(10),
            'status' => $this->faker->randomElement($statuses),

            // Le 'user_id' sera fourni par le Seeder
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
