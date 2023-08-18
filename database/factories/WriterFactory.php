<?php

namespace Database\Factories;

use Ybazli\Faker\Facades\Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Writer>
 */
class WriterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Faker::fullName(),
            'description' => Faker::paragraph(),
            'photo' => fake()->imageUrl($width=300, $height=300)
        ];
    }
}
