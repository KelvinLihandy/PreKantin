<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        dd($request);
        // $user = Auth::user();
        // $items = $request->items;

        // if (!$items || count($items) === 0) {
        //     return response()->json(['message' => 'Cart is empty'], 400);
        // }

        // $totalAmount = 0;

        // DB::transaction(function () use ($items, $user, &$totalAmount, &$order) {
        //     $order = Order::create([
        //         'user_id' => $user->user_id,
        //         'merchant_id' => MenuItem::find($items[0]['id'])->merchant_id,
        //         'status_id' => 1,
        //         'order_time' => now(),
        //         'gross_amount' => 0,
        //         'midtrans_status' => 'pending',
        //     ]);

        //     foreach ($items as $item) {
        //         $menuItem = MenuItem::find($item['id']);
        //         $quantity = $item['quantity'] ?? 1;

        //         $orderItem = OrderItem::create([
        //             'order_id' => $order->order_id,
        //             'menu_item_id' => $menuItem->menu_item_id,
        //             'quantity' => $quantity
        //         ]);

        //         $totalAmount += $orderItem->subtotal;
        //     }

        //     $order->update(['gross_amount' => $totalAmount]);
        // });

        // return response()->json(['invoice_number' => $order->invoice_number]);
    }
}
