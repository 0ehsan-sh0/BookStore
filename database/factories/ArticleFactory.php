<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $adminUserIds = User::where('role', 'admin')->pluck('id')->toArray();

        return [
            'title' => Faker::word(),
            'subtitle' => Faker::sentence(),
            'description' => Faker::paragraph(),
            'photo' => fake()->imageUrl($width = 1000, $height = 650),
            'user_id' => $adminUserIds[0],
        ];
    }
}
