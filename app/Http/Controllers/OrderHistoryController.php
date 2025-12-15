<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $storage = new SupabaseStorageService();
        if (Auth::user()->role->name !== 'Mahasiswa') {
            abort(403, __('error.only_mahasiswa'));
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['merchant.user', 'status', 'orderItems.menu_item'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) use ($storage) {
                $order->orderItems->map(function ($item) use ($storage) {
                    if ($item->menu_item && $item->menu_item->image) {
                        $item->menu_item->image_url = $storage->getImage(
                            $item->menu_item->image,
                            true
                        );
                    }
                    return $item;
                });

                return $order;
            });

        $activeOrders = $orders->whereIn('status_id', [1, 2, 3]);
        $finishedOrders = $orders->whereIn('status_id', [4, 5]);
        $orders = $activeOrders->concat($finishedOrders);

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
        $storage = new SupabaseStorageService();

        if ($order->user_id !== Auth::id()) {
            abort(403, __('error.no_order_access'));
        }

        $order->load(['orderItems.menu_item', 'merchant.user', 'status']);

        $order->orderItems->map(function ($item) use ($storage) {
            if ($item->menu_item && $item->menu_item->image) {
                $item->menu_item->image_url = $storage->getImage($item->menu_item->image, true);
            }
            return $item;
        });

        return view('order-detail', [
            'order' => $order
        ]);
    }

    public function merchantShow(Order $order)
    {
        $storage = new SupabaseStorageService();
        $user = Auth::user();

        if (!$user->merchant || $order->merchant_id !== $user->merchant->merchant_id) {
            abort(403, __('error.order_detail_forbidden'));
        }

        $order->load(['orderItems.menu_item', 'user', 'status']);

        $order->orderItems->map(function ($item) use ($storage) {
            if ($item->menu_item && $item->menu_item->image) {
                $item->menu_item->image_url = $storage->getImage($item->menu_item->image, true);
            }
            return $item;
        });


        return view('merchant.order-detail', compact('order'));
    }

    public function merchantIndex()
    {
        $storage = new SupabaseStorageService();
        $user = Auth::user();

        if (!$user->merchant) {
            abort(403, __('error.not_merchant'));
        }

        $merchantId = $user->merchant->merchant_id;

        $orders = Order::where('merchant_id', $merchantId)
            ->with(['user', 'status', 'orderItems.menu_item'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) use ($storage) {
                $order->orderItems->map(function ($item) use ($storage) {
                    if ($item->menu_item && $item->menu_item->image) {
                        $item->menu_item->image_url = $storage->getImage(
                            $item->menu_item->image,
                            true
                        );
                    }
                    return $item;
                });

                return $order;
            });;

        $activeOrders = $orders->whereIn('status_id', [1, 2, 3]);

        $groupedHistory = $orders->whereIn('status_id', [4, 5])
            ->groupBy(fn($order) => $order->order_time->format('Y'))
            ->map(fn($yearGroup) => $yearGroup->groupBy(fn($order) => $order->order_time->translatedFormat('F')));

        return view('merchant.order-history', compact('activeOrders', 'groupedHistory'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        $statusId = (int) $request->status_id;
        $merchantStatuses = [2, 3, 5];
        $studentStatuses = [4];

        if (in_array($statusId, $merchantStatuses)) {
            if (!$user->merchant) {
                abort(403, __('error.merchant_profile_not_found'));
            }
            if ($order->merchant_id != $user->merchant->merchant_id) {
                abort(403, __('error.no_permission_update_order'));
            }
        }

        if (in_array($statusId, $studentStatuses)) {
            if (!$user->user_id) {
                abort(403, __('error.student_profile_not_found'));
            }
            if ($order->user_id != $user->user_id) {
                abort(403, __('error.no_permission_update_order'));
            }
        }

        $order->status_id = $statusId;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
