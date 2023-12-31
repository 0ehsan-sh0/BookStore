<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;

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
        $isBook = random_int(0, 1);
        $isSentence = random_int(0, 1);
        do {
            $id = User::all()->random()->id;
            $adminId = User::where('role', 'admin')->first()->id;
        } while ($id === $adminId);
        if ($isBook) {
            return [
                'comment' => $isSentence ? Faker::sentence() : Faker::paragraph(),
                'status' => true,
                'book_id' => Book::all()->random()->id,
                'user_id' => $id,
            ];
        } else {
            return [
                'comment' => $isSentence ? Faker::sentence() : Faker::paragraph(),
                'status' => true,
                'article_id' => Article::all()->random()->id,
                'user_id' => $id,
            ];
        }
    }
}
