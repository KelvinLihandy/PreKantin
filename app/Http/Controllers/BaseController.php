<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public static function getNavbarData()
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'id' => null,
                'order_count' => 0,
                'fullName' => null,
                'displayName' => null,
                'role' => null,
            ];
        }

        $fullName = $user->name;
        $displayName = strlen($fullName) > 12 ? substr($fullName, 0, 12) . '...' : $fullName;
        $id = null;

        if ($user->role->name === 'Mahasiswa') {
            $order_count = Order::where('user_id', $user->user_id)
                ->whereIn('status_id', [1, 2, 3])
                ->count();
            $id = $user->user_id;
        } elseif ($user->role->name === 'Merchant') {
            $order_count = Order::whereHas('merchant', function ($q) use ($user) {
                $q->where('merchant_id', $user->merchant->merchant_id);
            })
                ->whereIn('status_id', [1, 2, 3])
                ->count();
            $id = $user->merchant->merchant_id;
        } else {
            $order_count = 0;
        }

        return [
            'id' => $id,
            'order_count' => $order_count,
            'fullName' => $fullName,
            'displayName' => $displayName,
            'role' => $user->role->name,
        ];
    }

    public function homePage()
    {
        $storage = new SupabaseStorageService();
        $topMenuItems = OrderItem::select('menu_item_id')
            ->selectRaw('COUNT(*) as total_orders')
            ->whereHas('order', function ($query) {
                $query->where('status_id', '<>', 5);
            })
            ->groupBy('menu_item_id')
            ->orderByDesc('total_orders')
            ->orderByDesc(
                MenuItem::select('updated_at')
                    ->whereColumn('menu_items.menu_item_id', 'order_items.menu_item_id')
                    ->limit(1)
            )
            ->with(['menu_item.merchant.user'])
            ->take(3)
            ->get()
            ->map(function ($item) use ($storage) {
                if ($item->menu_item && $item->menu_item->image) {
                    $item->menu_item->image_url = $storage->getImage(
                        $item->menu_item->image,
                        true
                    );
                }
                return $item;
            });

        return view('home', [
            'topMenuItems' => $topMenuItems,
        ]);
    }

    public function aboutPage()
    {
        return view('about');
    }
}
