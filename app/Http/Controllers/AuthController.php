<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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

    public function forgotPage()
    {
        return view('auth.forgot-password');
    }

    public function resetPage(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function changePage()
    {
        return view('auth.change-password');
    }

    public function registerAccount(RegisterRequest $request)
    {
        $data = $request->validated();
        try {
            $user = User::create([
                'role_id' => ($data['role'] === 'merchant') ? 2 : 1,
                'name'     => trim($data['name']),
                'email'    => trim($data['email']),
                'password' => $data['password'],
                'is_merchant' => $data['role'] === 'merchant',
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        if ($request->role === 'merchant') {
            try {
                Merchant::create([
                    'user_id' => $user->user_id,
                    'open' => null,
                    'close' => null,
                    'image' => null,
                ]);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        return redirect()->route('login')->with('success', __('register.toast'));
    }

    public function loginAccount(LoginRequest $request)
    {
        $validated = $request->validated();
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => __('auth.throttle',)
            ])->onlyInput('email');
        }

        if (!Auth::attempt(Arr::only($validated, ['email', 'password']), $request->boolean('remember', false))) {
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'email' => __('auth.failed'),
            ])->onlyInput('email');
        }
        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->intended(route('home.page'))->with([
            'success' => __('login.toast'),
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*\d)(?=.*[!@#$%])[A-Za-z\d!@#$%]{8,}$/',
            'password_confirmation' => 'required|same:password',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*\d)(?=.*[!@#$%])[A-Za-z\d!@#$%]{8,}$/',
            'confirmation' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => __('passwords.differ')]);
        }
        $user->password = $request->new_password;
        $user->save();

        return redirect()
            ->route('home.page')
            ->with('success', __('passwords.update'));
    }
}
