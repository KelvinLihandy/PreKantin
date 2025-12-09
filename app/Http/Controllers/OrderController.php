<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $menu = Menu::findOrFail($request->menu_id);

        Order::create([
            'user_id' => Auth::id(),
            'merchant_id' => $request->merchant_id,
            'menu_id' => $request->menu_id,
            'quantity' => $request->quantity,
            'status' => 'pending',
            'total_price' => $menu->price * $request->quantity,
        ]);

        return back();
    }
}
