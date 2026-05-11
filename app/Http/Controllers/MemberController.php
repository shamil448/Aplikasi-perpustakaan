<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MemberProfile;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // hanya mahasiswa + dosen
        $query->whereIn('role', ['mahasiswa', 'dosen']);

        // search
        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $members = $query->latest()->get();

        return view('anggota.index', compact('members'));
    }

    public function show($id)
    {
        $member = User::findOrFail($id);

        return view('anggota.show', compact('member'));
    }

    public function saveProfile(Request $request, $id)
    {
        $member = User::findOrFail($id);

        MemberProfile::updateOrCreate(

            [
                'user_id' => $member->id
            ],

            [
                'nim_nidn' => $request->nim_nidn,
                'fakultas' => $request->fakultas,
                'jurusan' => $request->jurusan,
                'nomor_hp' => $request->nomor_hp,
                'alamat' => $request->alamat,
            ]
        );

        return back()->with('success', 'Biodata berhasil disimpan');
    }
}
