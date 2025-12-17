<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseStorageService;

class KantinController extends Controller
{
    public function kantinPage($id)
    {
        $storage = new SupabaseStorageService();
        // Ambil merchant atau 404 jika tidak ada
        $merchant = Merchant::findOrFail($id);

        $menus = $merchant->menuItems()->latest()->get()->map(function ($menu) use ($storage) {
            $menu->image_url = $storage->getImage($menu->image, true);
            return $menu;
        });

        // Cek image exist hanya berdasarkan apakah ada value di DB
        $imageExist = !empty($merchant->image);

        // Cek user dan role
        $user = Auth::user();
        $isMerchant = $user && $user->role && $user->role->name === "Merchant";

        // Default values
        $isOpen = false;
        $orderCount = 0;
        $customerCount = 0;
        $total = 0;

        $imageUrl = $storage->getImage($merchant->image, false);

        // Hitung jam operasional
        if (!empty($merchant->open) && !empty($merchant->close)) {
            try {
                $tz = 'Asia/Jakarta';
                $now   = Carbon::now($tz);
                $open  = Carbon::createFromFormat('H:i:s', $merchant->open, $tz);
                $close = Carbon::createFromFormat('H:i:s', $merchant->close, $tz);

                if ($close->lessThan($open)) {
                    $close->addDay();
                }

                $isOpen = $now->between($open, $close);
            } catch (\Exception $e) {
                dd($e);
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
                $merchant->load('orders.orderItems.menu_item');
                $total = $merchant->orders->sum('totalPrice');
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
            'imageUrl'      => $imageUrl,
        ]);
    }

    public function kantinListPage(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role->name == "Merchant") {
            abort(403, __('error.access_denied'));
        }
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

        $storage = new SupabaseStorageService();

        $merchants->getCollection()->transform(function ($merchant) use ($storage) {
            if ($merchant->image) {
                $merchant->image_url = $storage->getImage($merchant->image, true);
            }
            return $merchant;
        });

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

    public function addMenu(Request $request)
    {
        $rules = [
            'nama_menu' => 'required|string|min:10|max:60',
            'harga'     => 'required|numeric|min:5000|max:30000',
            'merchant_id'  => 'required|numeric',
            'menu_item_id' => 'nullable|numeric',
        ];
        if (!$request->menu_item_id) {
            $rules['image'] = 'required|image|mimetypes:image/jpeg,image/png|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimetypes:image/jpeg,image/png|max:2048';
        }
        $request->validate($rules);

        $menu = null;
        $imageUrl = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'menu/' . uniqid() . '.' . $file->getClientOriginalExtension();

            $supabaseStorage = new SupabaseStorageService();
            $result = $supabaseStorage->upload(env('SUPABASE_BUCKET_MENU'), $path, $file);

            if ($result['success']) {
                $imageUrl = $result['public_url'];
            }
        }

        if ($request->menu_item_id) {
            $menu = MenuItem::findOrFail($request->menu_item_id);

            $dataToUpdate = [
                'merchant_id' => $request->merchant_id,
                'name'        => $request->nama_menu,
                'price'       => $request->harga,
            ];

            if ($imageUrl) {
                $dataToUpdate['image'] = $imageUrl;
            }

            $menu->update($dataToUpdate);
        } else {
            $menu = MenuItem::create([
                'merchant_id' => $request->merchant_id,
                'image'       => $imageUrl ?? 'temp',
                'name'        => $request->nama_menu,
                'price'       => $request->harga,
            ]);
        }

        return redirect()->back()->with('success', $request->menu_item_id ? __('menu.success.edit') : __('menu.success.add'));
    }
}
