<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handleCallback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $orderId = $notif->order_id;
        Log::info('Midtrans Notification:', (array) $notif);

        $order = Order::where('invoice_number', $orderId)->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found', 'notification' => (array) $notif], 404);
        }

        if ($transaction == 'capture') {
            if ($fraud == 'accept') {
                $this->updateStatus($order, 'paid', $notif);
            }
        } else if ($transaction == 'cancel') {
            $this->updateStatus($order, 'canceled', $notif);
        } else if ($transaction == 'deny') {
            $this->updateStatus($order, 'failed', $notif);
        } else if ($transaction == 'settlement') {
            $this->updateStatus($order, 'paid', $notif);
        }
    }

    protected function updateStatus(Order $order, string $status, $notif)
    {
        $order->update([
            'status_id' => in_array($status, ['paid', 'settlement']) ? 1 : 5
        ]);
        Payment::updateOrCreate(
            ['order_id' => $order->order_id],
            [
                'amount' => $notif->gross_amount,
                'status' => $status,
                'payment_date' => in_array($status, ['paid', 'settlement']) ? now() : null
            ]
        );
    }
}
