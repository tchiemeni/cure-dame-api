<?php

// database/factories/PostFactory.php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $types = ['video', 'audio', 'prayer'];
        $type = $this->faker->randomElement($types);

        return [
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->paragraph(3),
            'type' => $type,
            'media_url' => ($type != 'prayer') ? $this->faker->url() : null,
            'user_id' => \App\Models\User::factory(), // IMPORTANT
        ];
    }
}
