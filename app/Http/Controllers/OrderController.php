<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $user = Auth::user();
        $items = $request->items;
        $merchant_id = $request->merchant_id;

        $menuItemIds = collect($items)->pluck('id');

        $menuItems = MenuItem::whereIn('menu_item_id', $menuItemIds)
            ->get()
            ->keyBy('menu_item_id');

        $totalAmount = 0;
        $item_details = [];
        $tempItems = [];

        foreach ($items as $item) {
            $menuItem = $menuItems[$item['id']] ?? null;

            if (!$menuItem) {
                abort(400, 'Invalid menu item');
            }

            $subtotal = $menuItem->price * $item['quantity'];
            $totalAmount += $subtotal;

            $item_details[] = [
                'id'       => $menuItem->menu_item_id,
                'price'    => $menuItem->price,
                'quantity' => $item['quantity'],
                'name'     => $menuItem->name,
            ];

            $tempItems[] = [
                'menu_item_id' => $menuItem->menu_item_id,
                'name'         => $menuItem->name,
                'price'        => $menuItem->price,
                'quantity'     => $item['quantity'],
                'subtotal'     => $subtotal,
            ];
        }

        $invoiceNumber = 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        $tempOrder = [
            'invoice_number' => $invoiceNumber,
            'user_id'        => $user->user_id,
            'merchant_id'    => $merchant_id,
            'gross_amount'   => $totalAmount,
            'items'          => $tempItems,
        ];

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $nameParts = explode(' ', $user->name, 2);

        $transaction = [
            'transaction_details' => [
                'order_id'     => $invoiceNumber,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $nameParts[0],
                'last_name'  => $nameParts[1] ?? '',
                'email'      => $user->email,
            ],
            'item_details' => $item_details,
        ];

        try {
            $snap_token = \Midtrans\Snap::getSnapToken($transaction);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Snap token created',
            'data' => [
                'snap_token' => $snap_token,
                'order' => $tempOrder
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $orderData = $request->orderData;

        if (!$orderData) {
            return response()->json([
                'message' => 'Order data missing'
            ], 422);
        }

        $order = DB::transaction(function () use ($orderData, $user) {

            $order = Order::create([
                'user_id'        => $user->user_id,
                'merchant_id'    => $orderData['merchant_id'],
                'invoice_number' => $orderData['invoice_number'],
                'status_id'      => 1,
                'order_time'     => now(),
                'gross_amount'   => $orderData['gross_amount'],
            ]);

            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id'      => $order->order_id,
                    'menu_item_id'  => $item['menu_item_id'],
                    'price'         => $item['price'],
                    'quantity'      => $item['quantity'],
                ]);
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'invoice_number' => $order->invoice_number
            ]
        ]);
    }

    public function remove($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);
            OrderItem::where('order_id', $order->order_id)->delete();
            $order->delete();
        });
    }
}
