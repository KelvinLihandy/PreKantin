<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createQris(Request $request)
    {
        $total = $request->total;

        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            "payment_type" => "qris",
            "transaction_details" => [
                "order_id" => "ORDER-" . time(),
                "gross_amount" => $total,
            ]
        ];

        $response = \Midtrans\CoreApi::charge($params);

        return response()->json([
            "qr_string" => $response->actions[0]->qr_string ?? null,
            "response"  => $response
        ]);
    }
}
