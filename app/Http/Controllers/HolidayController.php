<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    // =============================
    // DAFTAR HARI LIBUR
    // =============================
    public function index()
    {
        $holidays = Holiday::orderBy('tanggal')->get();

        return view('holidays.index', compact('holidays'));
    }

    // =============================
    // HALAMAN TAMBAH
    // =============================
    public function create()
    {
        return view('holidays.create');
    }

    // =============================
    // SIMPAN
    // =============================
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:holidays,tanggal',
            'nama_libur' => 'required|string|max:255',
        ]);

        Holiday::create($request->all());

        return redirect('/holidays')
            ->with('success', 'Hari libur berhasil ditambahkan.');
    }

    // =============================
    // HALAMAN EDIT
    // =============================
    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);

        return view('holidays.edit', compact('holiday'));
    }

    // =============================
    // UPDATE
    // =============================
    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date|unique:holidays,tanggal,' . $holiday->id,
            'nama_libur' => 'required|string|max:255',
        ]);

        $holiday->update($request->all());

        return redirect('/holidays')
            ->with('success', 'Hari libur berhasil diubah.');
    }

    // =============================
    // HAPUS
    // =============================
    public function destroy($id)
    {
        Holiday::destroy($id);

        return redirect('/holidays')
            ->with('success', 'Hari libur berhasil dihapus.');
    }
}