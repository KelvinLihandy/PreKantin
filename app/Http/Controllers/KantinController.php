<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseStorageService;
use App\Services\SupabaseService;

class KantinController extends Controller
{
    public function kantinPage($id)
    {
        // Ambil merchant atau 404 jika tidak ada
        $merchant = Merchant::find($id);
        if (!$merchant) {
            abort(404, 'Merchant not found');
        }

        // Ambil daftar menu (jika relasi ada)
        $menus = method_exists($merchant, 'menuItems') ? $merchant->menuItems()->latest()->get() : collect();

        // Cek image exists (aman jika $merchant->image null)
        $imageExist = $merchant->image && file_exists(public_path($merchant->image));

        // Pastikan user ter-authenticate sebelum memanggil role
        $user = Auth::user();
        $isMerchant = false;
        if ($user && isset($user->role) && isset($user->role->name)) {
            $isMerchant = $user->role->name === "Merchant";
        }

        // Default values
        $isOpen = false;
        $orderCount = 0;
        $customerCount = 0;
        $total = 0;
        $orders = collect(); // pastikan selalu ada variable orders

        // Ambil orders berdasarkan role
        if ($isMerchant) {
            if (method_exists($merchant, 'orders')) {
                $orders = $merchant->orders()->with(['menu', 'orderItems'])->latest()->get();
            }
        } else {
            if ($user && method_exists($merchant, 'orders')) {
                $orders = $merchant->orders()
                    ->where('user_id', $user->id)
                    ->with(['menu', 'orderItems'])
                    ->latest()
                    ->get();
            }
        }

        // Hitung apakah buka/tutup (cek format jam terlebih dahulu)
        if (!empty($merchant->open) && !empty($merchant->close)) {
            try {
                $now = Carbon::now();
                $open = Carbon::createFromFormat('H:i', $merchant->open);
                $close = Carbon::createFromFormat('H:i', $merchant->close);

                // jika close < open (mis. buka 22:00 tutup 02:00), handle cross-midnight
                if ($close->lessThan($open)) {
                    // treat close as next day
                    $close->addDay();
                }

                $isOpen = $now->between($open, $close);
            } catch (\Exception $e) {
                // jika format jam salah, tetap false saja
                $isOpen = false;
            }
        }

        // Statistik untuk merchant
        if ($isMerchant && method_exists($merchant, 'orders')) {
            $orderCount = $merchant->orders()->count();
            $customerCount = $merchant->orders()->distinct('user_id')->count('user_id');
            // jika model Order menyimpan total_price langsung:
            if ($merchant->orders()->exists()) {
                $total = $merchant->orders()->sum('total_price');
            } else {
                // fallback: kumpulkan orderItems subtotal jika tersedia
                $total = $merchant->orders()
                    ->with('orderItems')
                    ->get()
                    ->flatMap->pluck('orderItems')
                    ->flatten()
                    ->sum('subtotal');
            }
        }

        // Kembalikan view — gunakan array supaya tidak typo pada compact()
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
