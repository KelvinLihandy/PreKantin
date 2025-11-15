<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $orders = Order::pluck('order_id')->toArray();
        $items = MenuItem::pluck('menu_item_id')->toArray();
        return [
            'order_id' => $this->faker->randomElement($orders),
            'menu_item_id' => $this->faker->randomElement($items),
            'quantity' => $this->faker->numberBetween(1, 3),
        ];
    }
}
