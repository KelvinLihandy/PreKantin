<?php

namespace Database\Factories;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $merchant_ids = Merchant::pluck('merchant_id')->toArray();

        return [
            'merchant_id' => $this->faker->randomElement($merchant_ids),
            'image' => $this->faker->imageUrl(400, 300, 'food', true),
            'name' => $this->faker->sentence(3, true),
            'price' => $this->faker->numberBetween(10000, 30000),
            'is_available' => $this->faker->boolean(90),
        ];
    }
}
