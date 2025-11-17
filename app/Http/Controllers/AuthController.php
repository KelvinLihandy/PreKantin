<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registerPage(Request $request)
    {
        $activeTab = $request->query('tab', 'mahasiswa');

        return view('auth.register', ['activeTab' => $activeTab]);
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function registerAccount(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'role_id' => ($data['role'] === 'merchant') ? 2 : 1,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_merchant' => $data['role'] === 'merchant',
        ]);
        if ($request->role === 'merchant') {
            Merchant::create([
                'user_id' => $user->user_id,
                'open' => null,
                'close' => null,
                'image' => null,
            ]);
        }

        return redirect()->route('login.page')->with('success', __('register.toast'));
    }

    public function loginAccount(LoginRequest $request)
    {
        $credentials = $request->validated();
        
        if (Auth::attempt($credentials, $request->boolean('remember', false))) {
            $request->session()->regenerate();

            return redirect()->intended('home.page')->with('success', __('login.toast'));;
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }
}
