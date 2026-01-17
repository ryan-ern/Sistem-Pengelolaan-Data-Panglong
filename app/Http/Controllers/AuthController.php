<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            notify()
                ->success(
                    'Selamat Datang ' . Auth::user()->nama,
                    'Login Berhasil'
                );

            return redirect()->intended('/dashboard');
        }

        notify()
            ->error(
                'Username atau Password Salah',
                'Gagal Login'
            );

        return redirect()->route('login');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        notify()
            ->success(
                'Berhasil Keluarkan Akun',
                'Logout Berhasil'
            );
        return redirect()->route('login');
    }
}
