<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Ybazli\Faker\Facades\Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isSentence = random_int(0, 1);
        return [
            'comment' => $isSentence ? Faker::sentence() : Faker::paragraph(),
            'book_id' => Book::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
