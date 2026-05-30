<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MemberProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    // ==========================
    // DAFTAR ANGGOTA
    // ==========================
    public function index(Request $request)
    {
        $query = User::query();

        $query->whereIn('role', ['mahasiswa', 'dosen']);

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $members = $query->latest()->get();

        return view('anggota.index', compact('members'));
    }

    // ==========================
    // DETAIL ANGGOTA
    // ==========================
    public function show($id)
    {
        $member = User::with('profile')->findOrFail($id);

        return view('anggota.show', compact('member'));
    }

    // ==========================
    // UPDATE BIODATA
    // ==========================
    public function saveProfile(Request $request, $id)
    {
        $member = User::with('profile')->findOrFail($id);

        $request->validate([
            'fakultas' => 'nullable|string|max:255',
            'jurusan'  => 'nullable|string|max:255',
            'alamat'   => 'nullable|string',
        ]);

        $profile = MemberProfile::firstOrCreate(
            [
                'user_id' => $member->id
            ]
        );

        // HANYA FIELD YANG BOLEH DIUBAH
        $profile->fakultas = $request->fakultas;
        $profile->jurusan  = $request->jurusan;
        $profile->alamat   = $request->alamat;

        $profile->save();

        return back()->with(
            'success',
            'Biodata berhasil diperbarui'
        );
    }

    public function resetPassword($id)
    {
        $member = User::findOrFail($id);

        $member->password = Hash::make('12345678');

        $member->save();

        return back()->with(
            'success',
            'Password berhasil direset menjadi: 12345678'
        );
    }
}
