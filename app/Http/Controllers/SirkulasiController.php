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

        $tanggalPinjam = Carbon::today();
        $tanggalKembali = Carbon::today()->addDays(2);

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
            ->where('status', 'dipinjam') // 🔥 penting
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

        $loan->tanggal_kembali = Carbon::parse($loan->tanggal_kembali)->addDays(2);
        $loan->is_extended = true;
        $loan->save();

        return back()->with('success', 'Berhasil diperpanjang 2 hari');
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
        // HITUNG DENDA BERDASARKAN TANGGAL SAJA
        // =========================
        $today = now()->startOfDay();
        $jatuhTempo = Carbon::parse($loan->tanggal_kembali)->startOfDay();

        $denda = 0;

        if ($today > $jatuhTempo) {
            $telatHari = $jatuhTempo->diffInDays($today);
            $denda = $telatHari * 1000;
        }

        // simpan nominal denda terbaru ke database
        $loan->denda = $denda;
        $loan->save();

        // =========================
        // VALIDASI DENDA
        // =========================
        if ($denda <= 0) {
            return back()->with('error', 'Tidak ada denda untuk dibayar');
        }

        // =========================
        // CONFIG MIDTRANS
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
        \Log::info('MIDTRANS CALLBACK MASUK', $request->all());

        $serverKey = config('midtrans.server_key');

        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($hashed !== $signatureKey) {
            \Log::error('Invalid signature dari Midtrans', [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ]);

            return response()->json(['error' => 'Invalid signature'], 403);
        }

        preg_match('/LOAN-(\d+)-/', $orderId, $matches);
        $loanId = $matches[1] ?? null;

        if (!$loanId) {
            return response()->json(['error' => 'Loan ID tidak valid'], 400);
        }

        $loan = Loan::find($loanId);

        if (!$loan) {
            return response()->json(['error' => 'Loan not found'], 404);
        }

        $transaction = $request->transaction_status;
        $fraud = $request->fraud_status ?? null;

        \Log::info('MIDTRANS STATUS', [
            'loan_id' => $loan->id,
            'status_lama' => $loan->status,
            'transaction' => $transaction,
            'fraud' => $fraud,
        ]);

        if ($transaction === 'settlement') {

            $jumlahDenda = $loan->denda > 0
                ? $loan->denda
                : $loan->denda_dibayar;

            $loan->denda_dibayar = $jumlahDenda;
            $loan->tanggal_bayar = now('Asia/Jakarta');
            $loan->status = 'lunas';
            $loan->denda = 0;
            $loan->save();
        }

        if ($transaction === 'capture') {

            if ($fraud === 'accept' || $fraud === null) {

                $jumlahDenda = $loan->denda > 0
                    ? $loan->denda
                    : $loan->denda_dibayar;

                $loan->denda_dibayar = $jumlahDenda;
                $loan->tanggal_bayar = now('Asia/Jakarta');
                $loan->status = 'lunas';
                $loan->denda = 0;
                $loan->save();
            }
        }

        if ($transaction === 'pending') {
            $loan->status = 'denda';
            $loan->save();
        }

        if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $loan->status = 'denda';
            $loan->save();
        }

        return response()->json(['success' => true]);
    }

    public function halamanDenda()
    {
        $loans = Loan::where('user_id', auth()->id())
            ->where('status', 'denda')
            ->with('book')
            ->latest()
            ->get();

        return view('mahasiswa.denda', compact('loans'));
    }

    public function aktivasiDenda($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->user_id != auth()->id()) {
            return back()->with('error', 'Akses ditolak');
        }

        // cek sudah lewat jatuh tempo
        if (now() <= $loan->tanggal_kembali) {
            return back()->with('error', 'Belum kena denda');
        }

        // ubah status
        $loan->status = 'denda';
        $loan->save();

        return redirect('/mahasiswa/denda')->with('success', 'Denda diaktifkan');
    }

    public function sejarah()
    {
        $loans = Loan::where('user_id', auth()->id())
            ->whereIn('status', ['lunas', 'kembali'])
            ->with('book', 'user')
            ->latest()
            ->get();

        return view('mahasiswa.sejarah', compact('loans'));
    }

    public function laporanKeuangan()
    {
        $loans = Loan::where('status', 'lunas')
            ->with(['book', 'user'])
            ->latest()
            ->get();

        $totalDendaLunas = $loans->sum('denda_dibayar');

        return view('staff.laporan-keuangan', compact('loans', 'totalDendaLunas'));
    }
}
