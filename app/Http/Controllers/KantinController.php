<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseStorageService;
use App\Services\SupabaseService;

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
        $isOpen = false;
        $orderCount = 0;
        $customerCount = 0;
        $total = 0;

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
        ]);
    }

    // Upload image ke Supabase Storage
    public function uploadMenuImage(Request $request, $merchantId)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $file = $request->file('image');
        $path = 'menu/' . uniqid() . '.' . $file->getClientOriginalExtension();

        $supabaseStorage = new SupabaseStorageService();
        $result = $supabaseStorage->upload(env('SUPABASE_BUCKET_MENU'), $path, $file);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Image berhasil diupload ke Supabase! URL: ' . $result['public_url']);
        }

        return redirect()->back()->with('error', 'Upload gagal: ' . $result['error']);
    }

    // Tambah menu ke Supabase
    public function addMenu(Request $request, $merchantId)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric',
            'kategori'  => 'nullable|string',
            'image'     => 'nullable|image|max:2048',
        ]);

        $gambarUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'menu/' . uniqid() . '.' . $file->getClientOriginalExtension();

            $supabaseStorage = new SupabaseStorageService();
            $result = $supabaseStorage->upload(env('SUPABASE_BUCKET_MENU'), $path, $file);

            if ($result['success']) {
                $gambarUrl = $result['public_url'];
            }
        }

        $supabase = new SupabaseService();
        $client = $supabase->getClient();
        $client->from('menu')->insert([
            'kantin_id' => $merchantId,
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
            'kategori'  => $request->kategori,
            'gambar_url'=> $gambarUrl,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }
}
