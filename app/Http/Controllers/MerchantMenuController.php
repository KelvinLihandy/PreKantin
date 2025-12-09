<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MerchantMenuController extends Controller
{
    public function index()
    {
        $menus = MenuItem::where('merchant_id', auth()->user()->merchant_id)->get();
        return view('merchant.menu.index', compact('menus'));
    }

    public function store(Request $request)
    {
        MenuItem::create([
            'merchant_id' => auth()->user()->merchant_id,
            'name' => $request->name,
            'price' => $request->price,
            'image_url' => $request->image_url
        ]);
        return back();
    }
}
