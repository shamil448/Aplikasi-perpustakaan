<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;

class SirkulasiController extends Controller
{
    // =============================
    // PINJAM BUKU
    // =============================
    public function pinjam(Request $request)
    {
        $request->validate([
            'kode' => 'required'
        ]);

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

        $tanggalPinjam = Carbon::now();
        $tanggalKembali = Carbon::now()->addDays(7);

        Loan::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'kode_eksemplar' => $kode,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dipinjam',
            'is_extended' => false,
            'denda' => 0
        ]);

        return back()->with([
            'judul' => $book->judul,
            'kode' => $kode,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali
        ]);
    }


    // =============================
    // PINJAMAN SAAT INI
    // =============================
    public function pinjamanSaatIni()
    {
        $loans = Loan::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->with('book')
            ->latest()
            ->get();

        return view('mahasiswa.pinjaman', compact('loans'));
    }


    // =============================
    // PERPANJANG
    // =============================
    public function perpanjang($id)
    {
        $loan = Loan::findOrFail($id);

        // hanya boleh milik sendiri
        if ($loan->user_id != auth()->id()) {
            return back()->with('error', 'Akses ditolak');
        }

        if ($loan->is_extended) {
            return back()->with('error', 'Buku sudah pernah diperpanjang');
        }

        // hanya bisa sebelum jatuh tempo
        if (Carbon::now() > $loan->tanggal_kembali) {
            return back()->with('error', 'Tidak bisa diperpanjang karena sudah lewat jatuh tempo');
        }

        $loan->tanggal_kembali = Carbon::parse($loan->tanggal_kembali)->addDays(7);
        $loan->is_extended = true;
        $loan->save();

        return back()->with('success', 'Berhasil diperpanjang 7 hari');
    }


    // =============================
    // DENDA
    // =============================
    public function denda($id)
    {
        $loan = Loan::findOrFail($id);

        // hanya milik sendiri
        if ($loan->user_id != auth()->id()) {
            return back()->with('error', 'Akses ditolak');
        }

        $today = Carbon::now();
        $jatuhTempo = Carbon::parse($loan->tanggal_kembali);

        if ($today <= $jatuhTempo) {
            return back()->with('error', 'Belum terlambat');
        }

        $telatHari = $jatuhTempo->diffInDays($today);
        $denda = $telatHari * 1000;

        // simpan ke database
        $loan->denda = $denda;
        $loan->save();

        return back()->with('success', "Denda: Rp " . number_format($denda));
    }

    public function halamanBayar($id)
    {
        $loan = Loan::with('book')->findOrFail($id);

        if ($loan->user_id != auth()->id()) {
            return back()->with('error', 'Akses ditolak');
        }

        // =========================
        // HITUNG DENDA
        // =========================
        $today = now();
        $jatuhTempo = $loan->tanggal_kembali;

        $denda = 0;

        if ($today > $jatuhTempo) {
            $telatHari = $jatuhTempo->diffInDays($today);
            $denda = $telatHari * 1000;
        }

        // =========================
        // VALIDASI DENDA
        // =========================
        if ($denda <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar');
        }

        // =========================
        // CONFIG MIDTRANS (HARD FIX 🔥)
        // =========================
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'LOAN-' . $loan->id . '-' . time(),
                'gross_amount' => (int) $denda,
            ],

            'item_details' => [
                [
                    'id' => $loan->id,
                    'price' => (int) $denda,
                    'quantity' => 1,
                    'name' => 'Denda Buku: ' . $loan->book->judul,
                ]
            ],

            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email ?? 'user@gmail.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('mahasiswa.bayar', compact('loan', 'denda', 'snapToken'));
    }

    public function callback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        $notif = new \Midtrans\Notification();

        $orderId = $notif->order_id;

        // ambil ID loan dari order_id
        preg_match('/LOAN-(\d+)-/', $orderId, $matches);
        $loanId = $matches[1] ?? null;

        $loan = Loan::find($loanId);

        if (!$loan) {
            return response()->json(['error' => 'Loan not found']);
        }

        if (in_array($notif->transaction_status, ['settlement', 'capture'])) {
            $loan->status = 'lunas';
            $loan->denda = 0;
            $loan->save();
        }

        return response()->json(['success' => true]);
    }

    public function halamanDenda()
    {
        $loans = Loan::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->with('book')
            ->latest()
            ->get();

        return view('mahasiswa.denda', compact('loans'));
    }
}
