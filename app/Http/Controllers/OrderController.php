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

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $items = $request->items;
        $merchant_id = $request->merchant_id;

        $totalAmount = 0;
        $orderData = null;

        $orderData = DB::transaction(function () use ($items, $user, &$totalAmount, &$order, $merchant_id) {
            $order = Order::create([
                'user_id' => $user->user_id,
                'merchant_id' => $merchant_id,
                'status_id' => 1,
                'order_time' => now(),
                'gross_amount' => 0,
            ]);

            foreach ($items as $item) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->order_id,
                    'menu_item_id' => $item['id'],
                    'quantity'     => $item['quantity'],
                ]);

                $totalAmount += $orderItem->subtotal;
            }

            $order->update(['gross_amount' => $totalAmount]);

            return $order;
        });

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $nameParts = explode(' ', $user->name, 2);
        $item_details = [];

        $transaction_details = [
            'order_id' => $orderData->invoice_number,
            'gross_amount' => $orderData->gross_amount,
        ];
        $customer_details = array(
            'first_name'    => $nameParts[0],
            'last_name'     => $nameParts[1],
            'email'         => $user->email,
            'phone'         => "",
        );
        foreach ($orderData->orderItems as $detail) {
            $item_details[] = [
                'id' => $detail->order_item_id,
                'price' => $detail->menu_item->price,
                'quantity' => $detail->quantity,
                'name' => $detail->menu_item->name,
            ];
        }
        $transaction = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        );

        try {
            $snap_token = \Midtrans\Snap::getSnapToken($transaction);
            Payment::updateOrCreate(
                ['order_id' => $orderData->order_id],
                [
                    'amount' => $orderData->gross_amount,
                    'status' => 'pending',
                    'snap_token' => $snap_token
                ]
            );
        } catch (\Exception $e) {
            $this->remove($orderData->order_id);
            echo $e->getMessage();
        }
        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibuat',
            'data' => [
                'order_id' => $orderData->order_id,
                'invoice_number' => $orderData->invoice_number,
                'gross_amount' => $totalAmount,
                'snap_token' => $snap_token,
            ]
        ], 201);
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
