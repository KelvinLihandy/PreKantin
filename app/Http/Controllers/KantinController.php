<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class KantinController extends Controller
{
    public function kantinPage($id)
    {
        // Ambil merchant atau 404 jika tidak ada
        $merchant = Merchant::findOrFail($id);

        // Ambil daftar menu
        $menus = $merchant->menuItems()->latest()->get();

        // Cek image exist hanya berdasarkan apakah ada value di DB
        $imageExist = !empty($merchant->image);

        // Cek user dan role
        $user = Auth::user();
        $isMerchant = $user && $user->role && $user->role->name === "Merchant";

        // Default values
        $orders = collect();
        $isOpen = false;
        $orderCount = 0;
        $customerCount = 0;
        $total = 0;

        // Ambil orders berdasarkan role
        if ($isMerchant) {
            $orders = $merchant->orders()
                ->with(['menu', 'orderItems'])
                ->latest()
                ->get();
        } else if ($user) {
            $orders = $merchant->orders()
                ->where('user_id', $user->id)
                ->with(['menu', 'orderItems'])
                ->latest()
                ->get();
        }

        // Hitung jam operasional
        if (!empty($merchant->open) && !empty($merchant->close)) {
            try {
                $now   = Carbon::now();
                $open  = Carbon::createFromFormat('H:i', $merchant->open);
                $close = Carbon::createFromFormat('H:i', $merchant->close);

                // Jika tutup < buka → cross-midnight
                if ($close->lessThan($open)) {
                    $close->addDay();
                }

                $isOpen = $now->between($open, $close);
            } catch (\Exception $e) {
                $isOpen = false;
            }
        }

        // Statistik khusus merchant
        if ($isMerchant) {
            $orderCount    = $merchant->orders()->count();
            $customerCount = $merchant->orders()
                ->distinct('user_id')
                ->count('user_id');

            if ($merchant->orders()->exists()) {
                $total = $merchant->orders()->sum('total_price');
            } else {
                // fallback: hitung total dari orderItems
                $total = $merchant->orders()
                    ->with('orderItems')
                    ->get()
                    ->pluck('orderItems')
                    ->flatten()
                    ->sum('subtotal');
            }
        }

        return view('kantin-detail', [
            'merchant'      => $merchant,
            'menus'         => $menus,
            'imageExist'    => $imageExist,
            'isMerchant'    => $isMerchant,
            'isOpen'        => $isOpen,
            'orderCount'    => $orderCount,
            'customerCount' => $customerCount,
            'total'         => $total,
            'orders'        => $orders,
        ]);
    }

    public function kantinListPage(Request $request)
    {
        $sort = $request->get('sort', 'name');

        $query = Merchant::with('user', 'menuItems')
            ->withAvg('menuItems', 'price')
            ->withCount('orders')
            ->has('menuItems')
            ->whereNotNull('image')
            ->whereNotNull('open')
            ->whereNotNull('close');

        if ($sort === 'price_asc') {
            $query->orderBy('menu_items_avg_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('menu_items_avg_price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $merchants = $query->paginate(12);

        if ($merchants->total() === 0) {
            $empty = new LengthAwarePaginator(
                collect(), // empty
                0,         // total items
                12,        // per page
                1,         // current page
                ['path' => request()->url(), 'query' => request()->query()]
            );

            $merchants = $empty;
        }

        return view('kantin-list', compact('merchants', 'sort'));
    }
}
