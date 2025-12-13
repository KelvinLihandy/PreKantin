<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchant_id = User::where('email', 'bconnect404@gmail.com')->first()->merchant->merchant_id;
        $merchant = Merchant::find($merchant_id);
        $user = User::where('email', 'kelvinlihandy2005@gmail.com')->first();
        $menu_items = $merchant->menuItems()->inRandomOrder()->take(3)->get();

        if($menu_items->count() < 3){
            $this->command->error('Not enough menu items for seeding order.');
            return;
        }

        DB::transaction(function () use ($user, $menu_items, $merchant) {
            $order = Order::create([
                'user_id' => $user->user_id,
                'merchant_id' => $merchant->merchant_id,
                'status_id' => 1,
                'order_time' => now(),
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'gross_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($menu_items as $item) {
                $quantity = rand(1, 3);
                $orderItem = OrderItem::create([
                    'order_id' => $order->order_id,
                    'menu_item_id' => $item->menu_item_id,
                    'quantity' => $quantity,
                ]);
                $totalAmount += $orderItem->subtotal;
            }
            $order->update(['gross_amount' => $totalAmount]);
        });
    }
}
