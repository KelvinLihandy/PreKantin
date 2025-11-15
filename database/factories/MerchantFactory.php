<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merchant>
 */
class MerchantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $merchant_users = User::where('is_merchant', true)->pluck('user_id')->toArray();

        return [
            'user_id' => $this->faker->unique()->randomElement($merchant_users),
            'image' => $this->faker->imageUrl(),
            'open' => $this->faker->time('H:i'),
            'close' => $this->faker->time('H:i'),
        ];
    }
}
