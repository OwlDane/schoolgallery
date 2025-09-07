<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ]);
            }
            
            // Log aktivitas login
            ActivityLog::log(
                'login',
                'Login ke sistem admin',
                $admin->id,
                null,
                [
                    'login_time' => now()->toDateTimeString(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            );
            
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout()
    {
        $admin = Auth::guard('admin')->user();
        
        // Log aktivitas logout
        if ($admin) {
            ActivityLog::log(
                'logout',
                'Logout dari sistem admin',
                $admin->id,
                null,
                [
                    'logout_time' => now()->toDateTimeString(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]
            );
        }
        
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    // =====================
    // Forgot + Reset Password
    // =====================

    // Tampilkan form lupa password
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    // Kirim email reset password
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Tampilkan form reset password
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    // Proses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                Auth::guard('admin')->login($admin);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.dashboard')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
