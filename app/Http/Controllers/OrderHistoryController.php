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

        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->order_time->format('Y'); // Group Tahun (2025, 2024)
        })->map(function ($yearGroup) {
            return $yearGroup->groupBy(function ($order) {
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

    public function merchantShow(Order $order)
    {
        $user = Auth::user();

        if (!$user->merchant || $order->merchant_id !== $user->merchant->merchant_id) {
            abort(403, 'Akses detail pesanan dilarang.');
        }

        $order->load(['orderItems.menu_item', 'user', 'status']);

        return view('merchant.order-detail', compact('order'));
    }

    public function merchantIndex()
    {
        $user = Auth::user();

        if (!$user->merchant) {
            abort(403, 'Akses ditolak. Anda tidak terdaftar sebagai Merchant.');
        }

        $merchantId = $user->merchant->merchant_id;

        $orders = Order::where('merchant_id', $merchantId)
            ->with(['user', 'status', 'orderItems.menu_item'])
            ->orderBy('order_time', 'desc')
            ->get();

        // Pesanan Aktif: Masuk (1), Diterima (2), dan Disiapkan (3)
        $activeOrders = $orders->whereIn('status_id', [1, 2, 3]);

        // Riwayat: Selesai (4) dan Ditolak (5)
        $groupedHistory = $orders->whereIn('status_id', [4, 5])
            ->groupBy(fn($order) => $order->order_time->format('Y'))
            ->map(fn($yearGroup) => $yearGroup->groupBy(fn($order) => $order->order_time->translatedFormat('F')));

        return view('merchant.order-history', compact('activeOrders', 'groupedHistory'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        if (!$user->merchant) {
            abort(403, 'Profil Merchant tidak ditemukan.');
        }

        $merchantId = $user->merchant->merchant_id;

        if ($order->merchant_id != $merchantId) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah pesanan ini.');
        }

        $order->status_id = $request->status_id;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}