<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;

class SirkulasiController extends Controller
{
    public function pinjam(Request $request)
    {
        $kode = $request->kode;

        $book = Book::where('eksemplar', $kode)->first();

        if (!$book) {
            return back()->with('error', 'Kode eksemplar tidak ditemukan');
        }

        // CEK apakah buku sedang dipinjam orang lain
        $sedangDipinjam = Loan::where('kode_eksemplar', $kode)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sedangDipinjam) {

            return back()->with('error', 'Buku dengan kode eksemplar ini sedang dipinjam');
        }

        // CEK batas maksimal pinjam mahasiswa
        $jumlahPinjaman = Loan::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->count();

        if ($jumlahPinjaman >= 2) {

            return back()->with('error', 'Batas buku dipinjam hanya 2 buah');
        }

        $tanggalPinjam = now();
        $tanggalKembali = now()->addDays(7);

        Loan::create([

            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'kode_eksemplar' => $kode,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dipinjam'

        ]);

        return back()->with([
            'judul' => $book->judul,
            'kode' => $kode,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali
        ]);
    }

    public function pinjamanSaatIni()
    {

        $loans = \App\Models\Loan::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->with('book')
            ->get();

        return view('mahasiswa.pinjaman', compact('loans'));
    }
}
