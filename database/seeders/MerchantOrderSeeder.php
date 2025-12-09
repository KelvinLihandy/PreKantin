<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Status;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MerchantOrderSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('is_merchant', false)->first();
        $merchant = Merchant::first();

        if (!$student || !$merchant) {
            $this->command->info('Pastikan Anda sudah memiliki user dan merchant di database!');
            return;
        }

        $menus = MenuItem::factory()->count(5)->create([
            'merchant_id' => $merchant->merchant_id
        ]);

        $incomingOrder = Order::create([
            'user_id' => $student->user_id,
            'merchant_id' => $merchant->merchant_id,
            'status_id' => 1,
            'order_time' => now(),
        ]);

        OrderItem::create([
            'order_id' => $incomingOrder->order_id,
            'menu_item_id' => $menus->random()->menu_item_id,
            'quantity' => 2
        ]);

        $historyOrder = Order::create([
            'user_id' => $student->user_id,
            'merchant_id' => $merchant->merchant_id,
            'status_id' => 4,
            'order_time' => now()->subYear(),
        ]);

        OrderItem::create([
            'order_id' => $historyOrder->order_id,
            'menu_item_id' => $menus->random()->menu_item_id,
            'quantity' => 1
        ]);

        $this->command->info('Data Dummy Merchant Berhasil Diisi!');
    }
}