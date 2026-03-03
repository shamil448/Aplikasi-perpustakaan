<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Form Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
            'role' => 'required|in:staff,mahasiswa,dosen'
        ]);

        User::create([
            'name' => $request->name,
            'password' => $request->password,
            'role' => $request->role
        ]);

        return redirect('/login');
    }

    // FORM LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'staff') {
                return redirect('/staff/dashboard');
            }

            if ($user->role === 'mahasiswa') {
                return redirect('/mahasiswa/dashboard');
            }

            if ($user->role === 'dosen') {
                return redirect('/dosen/dashboard');
            }
        }

        return back()->with('error', 'Login Gagal');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
