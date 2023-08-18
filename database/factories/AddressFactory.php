<?php

namespace Database\Factories;

use App\Models\User;
use Ybazli\Faker\Facades\Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Faker::firstname(),
            'lastname' => Faker::lastname(),
            'phone' => Faker::mobile(),
            'place_number' => random_int(10000000,99999999),
            'post_code' => random_int(1000000000,9999999999),
            'state' => Faker::state(),
            'city' => Faker::city(),
            'address' => Faker::address(),
            'user_id' => User::all()->random()->id
        ];
    }
}
