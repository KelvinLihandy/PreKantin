<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KantinController extends Controller
{
    public function kantinPage($id)
    {
        $merchant = Merchant::find($id);
        $menus = $merchant->menuItems()->latest()->get();
        $imageExist = $merchant->image && file_exists(public_path($merchant->image));
        $isMerchant = Auth::user()->role->name == "Merchant";
        $isOpen = false;
        $orderCount = 0;
        $customerCount = 0;
        $total = 0;

        if ($merchant->open && $merchant->close) {
            $now = Carbon::now();
            $open = Carbon::createFromFormat('H:i', $merchant->open);
            $close = Carbon::createFromFormat('H:i', $merchant->close);
            $isOpen = $now->between($open, $close);
        }
        if ($isMerchant) {
            $orderCount = $merchant->orders()->count();
            $customerCount = $merchant->orders()->distinct('user_id')->count('user_id');
            $total = $merchant->orders()
                ->with('orderItems')
                ->get()
                ->flatMap->orderItems
                ->sum('subtotal');
        }

        return view('kantin-detail', compact(
            'merchant',
            'menus',
            'imageExist',
            'isMerchant',
            'isOpen',
            'orderCount',
            'customerCount',
            'total'
        ));
    }
}
