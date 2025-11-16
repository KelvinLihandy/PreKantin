<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function homePage()
    {
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
            ->get();

        return view('home', ['topMenuItems' => $topMenuItems]);
    }
}
