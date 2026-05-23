<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show login page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUserBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Handle authentication.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'NIM atau Email harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        $loginInput = $request->input('email');
        $password = $request->input('password');

        // Check if the input is a valid email format, otherwise treat it as NIM
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return $this->redirectUserBasedOnRole($user)->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Identitas login atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Helper to redirect based on role.
     */
    private function redirectUserBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'counselor') {
            return redirect()->intended(route('counselor.dashboard'));
        } else {
            return redirect()->intended(route('konseli.dashboard'));
        }
    }
}
