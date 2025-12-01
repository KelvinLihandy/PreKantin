<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderHistoryController extends Controller
{
    public function index()
    {
        if (Auth::user()->role->name !== 'Mahasiswa') {
            abort(403, 'Halaman ini khusus Mahasiswa.'); 
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['merchant.user', 'status', 'orderItems.menu_item']) // Load relasi biar ga berat
            ->orderBy('order_time', 'desc')
            ->get();

        $groupedOrders = $orders->groupBy(function($order) {
            return $order->order_time->format('Y'); // Group Tahun (2025, 2024)
        })->map(function($yearGroup) {
            return $yearGroup->groupBy(function($order) {
                return $order->order_time->translatedFormat('F'); // Group Bulan (November, Oktober)
            });
        });

        return view('order-history', [
            'groupedOrders' => $groupedOrders
        ]);
    }

    public function show(Order $order)
    {
        // Cek keamanan: Pastikan yang buka detail ini adalah pemilik ordernya
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Load detail item dan menu-nya
        $order->load(['orderItems.menu_item', 'merchant.user', 'status']);

        return view('order-detail', [
            'order' => $order
        ]);
    }
}