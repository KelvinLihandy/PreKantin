<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    public function saveName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:5|max:255|regex:/^[A-Za-z ]+$/',
            'merchant_id' => 'required|exists:merchants,merchant_id'
        ]);

        $merchant = Merchant::findOrFail($request->merchant_id);

        if (Auth::id() !== $merchant->user_id) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized']);
        }

        $merchant->user->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', __('merchant.edit.name'));
    }

    public function saveTime(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required|exists:merchants,merchant_id',
            'open' => 'required|date_format:H:i',
            'close' => 'required|date_format:H:i|after:open'
        ]);

        $merchant = Merchant::findOrFail($request->merchant_id);

        if (Auth::id() !== $merchant->user_id) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized']);
        }

        $merchant->update([
            'open' => $request->open . ':00',
            'close' => $request->close . ':00'
        ]);

        return redirect()->back()->with('success', 'Operating hours updated successfully');
    }

    public function saveImage(Request $request)
    {
        $merchant = Merchant::findOrFail($request->merchant_id);

        $rules = [
            'merchant_id' => 'required|exists:merchants,merchant_id',
        ];

        $hasImage = !empty($merchant->image) && $merchant->image !== 'temp';

        if ($hasImage) {
            $rules['image'] = 'nullable|image|mimetypes:image/jpeg,image/png|max:2048';
        } else {
            $rules['image'] = 'required|image|mimetypes:image/jpeg,image/png|max:2048';
        }

        $request->validate($rules);

        if (Auth::id() !== $merchant->user_id) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'merchant/' . uniqid() . '.' . $file->getClientOriginalExtension();

            $supabaseStorage = new SupabaseStorageService();
            $result = $supabaseStorage->upload(env('SUPABASE_BUCKET_MERCHANT'), $path, $file);

            if ($result['success']) {
                $merchant->update([
                    'image' => $result['public_url']
                ]);

                $message = $hasImage ? __('merchant.success.edit') : __('merchant.success.add');
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()->withErrors(['image' => __('menu.image.fail')])->withInput();
            }
        }

        if ($hasImage) {
            return redirect()->back()->with('info', __('merchant.edit.void'));
        }

        return redirect()->back()->withErrors(['image' => __('merchant.edit.image.error')]);
    }
}
