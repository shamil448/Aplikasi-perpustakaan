<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MemberProfile;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // FORM REGISTER
    public function showRegister()
    {
        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'username' => 'required|string|max:255|unique:users,username',

            'nim_nidn' => 'required|string|max:255',

            'nomor_hp' => 'required|string|max:20',

            'password' => 'required|string|min:6',

            'role' => 'required|in:staff,mahasiswa,dosen',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,

            // email tetap diisi dummy karena kolom email di database masih required + unique
            'email' => $request->username . '@perpus.local',

            'password' => $request->password,
            'role' => $request->role,
        ]);

        MemberProfile::create([
            'user_id' => $user->id,
            'nim_nidn' => $request->nim_nidn,
            'nomor_hp' => $request->nomor_hp,
        ]);

        return redirect('/login')->with('success', 'Register berhasil, silakan login');
    }

    // FORM LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            $user->is_online = true;
            $user->save();

            if ($user->role === 'staff') {
                return redirect('/staff/dashboard');
            }

            if ($user->role === 'mahasiswa') {
                return redirect('/mahasiswa/dashboard');
            }

            if ($user->role === 'dosen') {
                return redirect('/dosen/dashboard');
            }

            return redirect('/login');
        }

        return back()
            ->withInput()
            ->with('error', 'Username atau kata sandi salah');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->is_online = false;
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
