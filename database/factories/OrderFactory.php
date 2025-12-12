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
        $merchant = Merchant::whereHas('user', function($query) {
            $query->where('is_merchant', true);
        })->inRandomOrder()->first();

        $user = User::where('is_merchant', false)->inRandomOrder()->first();

        return [
            'user_id' => $user ? $user->user_id : 1,
            'merchant_id' => $merchant ? $merchant->merchant_id : 1,
            'status_id' => Status::inRandomOrder()->first()->status_id,
            'order_time' => now(),
        ];
    }
}
