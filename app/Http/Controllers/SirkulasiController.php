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

        // cari buku berdasarkan kode eksemplar
        $book = Book::Where('eksemplar', $kode)->first();

        if (!$book) {
            return back()->with('error', 'Kode eksemplar tidak ditemukan');
        }

        $tanggalPinjam = Carbon::now();
        $tanggalKembali = Carbon::now()->addDays(7);

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
}
