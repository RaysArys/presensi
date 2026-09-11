<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:staff,admin,supervisor',
        ]);
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['username'=>'Username, password, atau role tidak sesuai.'])->onlyInput('username');
        }
        if (auth()->user()->status_akun !== 'aktif') {
            Auth::logout();
            return back()->withErrors(['username'=>'Akun Anda sedang dinonaktifkan.']);
        }
        $request->session()->regenerate();
        return auth()->user()->role === 'admin' ? redirect()->route('admin.dashboard') : redirect()->route('user.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
