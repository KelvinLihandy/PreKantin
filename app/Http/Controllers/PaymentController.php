<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Membuat transaksi QRIS baru dengan Midtrans.
     */
    public function create(Request $request)
    {
        // 1. Ambil data User dan Total
        $user = auth()->user();
        $raw_total = $request->input('total') ?? ''; 
        
        $clean_total = (int)preg_replace('/[^0-9]/', '', $raw_total); 
        $total = $clean_total; 

        $customer_name = $user->name ?? 'Pengguna Prekantin';
        $customer_email = $user->email ?? 'guest_default@prekantin.com';
        $customer_phone = $user->phone ?? '081234567890'; 

        if (!is_numeric($total) || $total < 100) { 
            return response()->json([
                'success' => false,
                'message' => 'Total pembayaran tidak valid atau kurang dari minimum Midtrans (Rp 100). Total terdeteksi: ' . $total
            ], 400);
        }

        // 2. Konfigurasi Midtrans
        // PASTIKAN .env ANDA SUDAH BENAR: MIDTRANS_SERVER_KEY dan MIDTRANS_IS_PRODUCTION
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); 
        Config::$isSanitized = true; 
        Config::$is3ds = true;
        
        $order_id = 'ORD-' . time() . '-' . rand(1000, 9999);

        // 3. Siapkan Parameter Transaksi (PAYLOAD)
        $params = array(
            'payment_type' => 'qris',
            'transaction_details' => array(
                'order_id' => $order_id,
                'gross_amount' => $total, 
            ),
            'customer_details' => array(
                'first_name' => $customer_name, 
                'email' => $customer_email,
                'phone' => $customer_phone,
            ),
            'item_details' => [
                [
                    'id' => 'PREKANTIN-ITEM-' . time(),
                    'price' => $total,
                    'quantity' => 1,
                    'name' => 'Total Pembelian Kantin'
                ]
            ],
            'qris' => [
                'acquirer_bank' => 'bca', 
                'recurring' => false
            ],
            'enabled_payments' => ['qris'], 
        );

        try {
            // Panggil API Midtrans
            $response = $this->callMidtransChargeApi($params);
            
            // 4. Analisis Respons Midtrans
            if (!is_object($response) || !isset($response->status_code)) {
                 $error_msg = 'Server Key salah atau Midtrans API tidak merespons dengan JSON yang valid.';
                 throw new \Exception("Midtrans API Fatal Error: {$error_msg}");
            }
            
            if (isset($response->status_code) && $response->status_code == '201') {
                $qr_url = null;
                
                if (isset($response->actions) && is_array($response->actions)) {
                    foreach ($response->actions as $action) {
                        if (isset($action->name) && $action->name == 'generate-qr-code') {
                            $qr_url = $action->url;
                            break;
                        }
                    }
                }

                if ($qr_url) {
                    return response()->json([
                        'success' => true,
                        'message' => 'QRIS berhasil dibuat.',
                        'qr_url' => $qr_url,
                        'order_id' => $order_id
                    ]);
                }
            }
            
            $midtrans_message = $response->status_message[0] ?? 'Unknown Midtrans Error (Response Code: ' . ($response->status_code ?? 'N/A') . ')';
            return response()->json([
                'success' => false,
                'message' => "Gagal membuat transaksi Midtrans. Detail: {$midtrans_message}",
            ], 500);

        } catch (\Exception $e) {
            Log::error('Midtrans Payment Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]); 
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan Server Internal atau Koneksi Midtrans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fungsi helper untuk memanggil Midtrans API menggunakan cURL murni.
     */
    private function callMidtransChargeApi($params)
    {
        $midtrans_url = Config::getBaseUrl() . '/v2/charge';
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $midtrans_url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode(Config::$serverKey . ':') 
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            $curl_info = curl_getinfo($curl);
            curl_close($curl);
            throw new \Exception("cURL FAILED. Message: {$error_msg}. (HTTP Code: {$curl_info['http_code']})");
        }
        
        curl_close($curl);
        return json_decode($result);
    }
}