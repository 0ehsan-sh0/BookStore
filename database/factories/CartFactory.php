<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Cart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Cart::where('code', $code)->count() > 0);
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        $randomDateTime = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));

        return [
            'code' => $code,
            'ischeckedout_at' => $randomDateTime,
            'total_price' => 0,
            'address_id' => Address::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
