<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('is_merchant', false)->inRandomOrder()->first()->user_id,
            'merchant_id' => Merchant::inRandomOrder()->first()->merchant_id,
            'status_id' => Status::inRandomOrder()->first()->status_id,
            'order_time' => now(),
        ];
    }
}
