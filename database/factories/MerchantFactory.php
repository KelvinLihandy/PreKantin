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
        $merchant_users = User::where('is_merchant', true)
            ->where('email', '!=', 'bconnect404@gmail.com')->pluck('user_id')->toArray();
        $openHour = $this->faker->numberBetween(6, 18);
        $openMinute = $this->faker->numberBetween(0, 59);
        $open = sprintf('%02d:%02d', $openHour, $openMinute);
        $closeHour = $this->faker->numberBetween($openHour + 1, 22);
        $closeMinute = $this->faker->numberBetween(0, 59);
        $close = sprintf('%02d:%02d', $closeHour, $closeMinute);

        return [
            'user_id' => $this->faker->unique()->randomElement($merchant_users),
            'image' => 'images/dummyKantin.png',
            'open' => $open,
            'close' => $close,
        ];
    }
}
