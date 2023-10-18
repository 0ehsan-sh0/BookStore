<?php

namespace Database\Factories;

use App\Models\Writer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomInt = mt_rand(100, 999); // Generate a random 3-digit number
        $randomIntString1 = str_pad($randomInt, 3, '0', STR_PAD_LEFT); // Pad the number with leading zeros
        $randomInt = mt_rand(100, 999); // Generate a random 3-digit number
        $randomIntString2 = str_pad($randomInt, 3, '0', STR_PAD_LEFT); // Pad the number with leading zeros
        $randomInt = mt_rand(100, 999); // Generate a random 3-digit number
        $randomIntString3 = str_pad($randomInt, 3, '0', STR_PAD_LEFT); // Pad the number with leading zeros
        $randomInt = mt_rand(100, 999); // Generate a random 3-digit number
        $randomIntString4 = str_pad($randomInt, 3, '0', STR_PAD_LEFT); // Pad the number with leading zeros
        $randomIntString5 = mt_rand(1, 9); // Generate a random 3-digit number

        $randomFormat = "{$randomIntString4}-{$randomIntString3}-{$randomIntString2}-{$randomIntString1}-{$randomIntString5}";
        $randomPrice = random_int(50, 400);

        return [
            'code' => random_int(100000000, 999999999),
            'name' => Faker::word(),
            'english_name' => fake()->word(),
            'description' => Faker::paragraph(),
            'price' => $randomPrice * 1000,
            'photo' => fake()->imageUrl($width = 560, $height = 900),
            'print_series' => random_int(1, 50),
            'isbn' => $randomFormat,
            'book_cover_type' => Faker::word(),
            'format' => Faker::word(),
            'pages' => random_int(20, 350),
            'publish_year' => random_int(1370, 1400),
            'publisher' => Faker::word(),
            'count' => random_int(50, 1000),
            'writer_id' => Writer::all()->random()->id,
        ];
    }
}
