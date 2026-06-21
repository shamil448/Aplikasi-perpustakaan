<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;

use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Midtrans\Config;
use Midtrans\Snap;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;

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

        // =====================================
        // CEK APAKAH MASIH ADA DENDA AKTIF
        // =====================================

        $punyaDenda = Loan::where('user_id', auth()->id())
            ->where(function ($query) {

                $query->where('status', 'denda')

                    ->orWhere(function ($q) {

                        $q->where('status', 'dipinjam')
                            ->whereDate(
                                'tanggal_kembali',
                                '<',
                                now()->toDateString()
                            );
                    });
            })
            ->exists();

        if ($punyaDenda) {

            return back()->with(
                'error',
                'Anda masih memiliki denda yang belum dilunasi. Silakan lunasi denda terlebih dahulu.'
            );
        }

        // =====================================
        // PROSES PINJAM
        // =====================================

        $kode = $request->kode;

        $book = Book::where('eksemplar', $kode)->first();

        if (!$book) {

            return back()->with(
                'error',
                'Kode eksemplar tidak ditemukan'
            );
        }

        // =====================================
        // CEK BUKU SEDANG DIPINJAM
        // =====================================

        $sedangDipinjam = Loan::where(
            'kode_eksemplar',
            $kode
        )
            ->where('status', 'dipinjam')
            ->exists();

        if ($sedangDipinjam) {

            return back()->with(
                'error',
                'Buku dengan kode eksemplar ini sedang dipinjam'
            );
        }

        // =====================================
        // BATAS MAKSIMAL 2 BUKU
        // =====================================

        $jumlahPinjaman = Loan::where(
            'user_id',
            auth()->id()
        )
            ->where('status', 'dipinjam')
            ->count();

        if ($jumlahPinjaman >= 2) {

            return back()->with(
                'error',
                'Batas buku dipinjam hanya 2 buah'
            );
        }

        // =====================================
        // SIMPAN PINJAMAN
        // =====================================

        $tanggalPinjam = Carbon::today();

        $tanggalKembali = Carbon::today()
            ->addDays(2);

        $loan = Loan::create([

            'user_id' => auth()->id(),

            'book_id' => $book->id,

            'kode_eksemplar' => $kode,

            'tanggal_pinjam' => $tanggalPinjam,

            'tanggal_kembali' => $tanggalKembali,

            'status' => 'dipinjam',

            'is_extended' => false,

            'denda' => 0,

            'wa_denda_terkirim' => false,

            'wa_lunas_terkirim' => false,

            'wa_reminder_terkirim' => false,
        ]);

        // =====================================
        // KIRIM WA PEMINJAMAN BERHASIL
        // =====================================

        $user = auth()->user()->load('profile');

        $nomor = $user->profile->nomor_hp ?? null;

        if ($nomor) {

            $nomor = preg_replace('/^0/', '62', $nomor);

            $nama = $user->name;

            $pesan =
                "Halo {$nama} 📚\n\n" .

                "Peminjaman buku berhasil.\n\n" .

                "Judul Buku : {$book->judul}\n" .
                "Kode Buku : {$kode}\n\n" .

                "Tanggal Pinjam : " .
                $tanggalPinjam->format('d-m-Y') . "\n" .

                "Tanggal Kembali : " .
                $tanggalKembali->format('d-m-Y') . "\n\n" .

                "Selamat membaca dan jangan lupa mengembalikan tepat waktu 🙏\n\n" .

                "Perpustakaan Digital";

            try {

                Http::withHeaders([
                    'Authorization' => config('services.fonnte.token')
                ])->post(
                    'https://api.fonnte.com/send',
                    [
                        'target' => $nomor,
                        'message' => $pesan,
                    ]
                );
            } catch (\Exception $e) {

                \Log::error('WA Pinjam Gagal', [
                    'loan_id' => $loan->id,
                    'message' => $e->getMessage()
                ]);
            }
        }

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
        $loan = Loan::with([
            'user.profile',
            'book'
        ])->findOrFail($id);

        // hanya boleh milik sendiri
        if ($loan->user_id != auth()->id()) {
            return back()->with('error', 'Akses ditolak');
        }

        if ($loan->is_extended) {
            return back()->with('error', 'Buku sudah pernah diperpanjang');
        }

        // hanya bisa sebelum jatuh tempo
        if (Carbon::now() > $loan->tanggal_kembali) {
            return back()->with(
                'error',
                'Tidak bisa diperpanjang karena sudah lewat jatuh tempo'
            );
        }

        $loan->tanggal_kembali = Carbon::parse(
            $loan->tanggal_kembali
        )->addDays(2);

        $loan->is_extended = true;

        $loan->save();

        // =====================================
        // KIRIM WA PERPANJANGAN BERHASIL
        // =====================================

        $nomor = $loan->user->profile->nomor_hp ?? null;

        if ($nomor) {

            $nomor = preg_replace('/^0/', '62', $nomor);

            $nama = $loan->user->name;

            $tanggalKembaliBaru = Carbon::parse(
                $loan->tanggal_kembali
            )->format('d-m-Y');

            $pesan =
                "Halo {$nama} 📚\n\n" .

                "Perpanjangan masa peminjaman buku berhasil.\n\n" .

                "Judul Buku : {$loan->book->judul}\n" .
                "Kode Buku : {$loan->kode_eksemplar}\n\n" .

                "Tanggal Kembali Baru : {$tanggalKembaliBaru}\n\n" .

                "Silakan kembalikan buku sebelum tanggal tersebut untuk menghindari denda keterlambatan.\n\n" .

                "Terima kasih 🙏\n" .
                "Perpustakaan Digital";

            try {

                Http::withHeaders([
                    'Authorization' => config('services.fonnte.token')
                ])->post(
                    'https://api.fonnte.com/send',
                    [
                        'target' => $nomor,
                        'message' => $pesan,
                    ]
                );
            } catch (\Exception $e) {

                \Log::error('WA Perpanjang Gagal', [
                    'loan_id' => $loan->id,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return back()->with(
            'success',
            'Berhasil diperpanjang 2 hari'
        );
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

        $hashed = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if ($hashed !== $signatureKey) {

            \Log::error('Invalid signature dari Midtrans', [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ]);

            return response()->json([
                'error' => 'Invalid signature'
            ], 403);
        }

        preg_match('/LOAN-(\d+)-/', $orderId, $matches);

        $loanId = $matches[1] ?? null;

        if (!$loanId) {
            return response()->json([
                'error' => 'Loan ID tidak valid'
            ], 400);
        }

        $loan = Loan::with([
            'user.profile',
            'book'
        ])->find($loanId);

        if (!$loan) {
            return response()->json([
                'error' => 'Loan not found'
            ], 404);
        }

        $transaction = $request->transaction_status;
        $fraud = $request->fraud_status ?? null;

        \Log::info('MIDTRANS STATUS', [
            'loan_id' => $loan->id,
            'status_lama' => $loan->status,
            'transaction' => $transaction,
            'fraud' => $fraud,
        ]);

        // ===================================================
        // PEMBAYARAN BERHASIL
        // ===================================================
        if (
            $transaction === 'settlement' ||
            (
                $transaction === 'capture' &&
                ($fraud === 'accept' || $fraud === null)
            )
        ) {

            $jumlahDenda = $loan->denda > 0
                ? $loan->denda
                : $loan->denda_dibayar;

            $loan->denda_dibayar = $jumlahDenda;
            $loan->tanggal_bayar = now('Asia/Jakarta');
            $loan->status = 'lunas';
            $loan->denda = 0;

            $loan->save();

            // ==================================
            // WA LUNAS HANYA 1 KALI
            // ==================================
            if (!$loan->wa_lunas_terkirim) {

                $nomor = $loan->user->profile->nomor_hp ?? null;

                if ($nomor) {

                    $nomor = preg_replace('/^0/', '62', $nomor);

                    $nama = $loan->user->name;

                    $nominalBayar = number_format(
                        $loan->denda_dibayar,
                        0,
                        ',',
                        '.'
                    );

                    $pesan =
                        "Halo {$nama} 📚\n\n" .

                        "Pembayaran denda Anda berhasil.\n\n" .

                        "Judul Buku : {$loan->book->judul}\n" .
                        "Kode Buku : {$loan->kode_eksemplar}\n" .
                        "Nominal Dibayar : Rp {$nominalBayar}\n\n" .

                        "Status Denda : LUNAS ✅\n\n" .

                        "Sekarang Anda sudah dapat melakukan peminjaman buku kembali.\n\n" .

                        "Tanggal Bayar : " .
                        $loan->tanggal_bayar->format('d-m-Y H:i:s') .
                        "\n\n" .

                        "Terima kasih telah menggunakan Sistem Perpustakaan 🙏";

                    try {

                        Http::withHeaders([
                            'Authorization' => config('services.fonnte.token')
                        ])->post(
                            'https://api.fonnte.com/send',
                            [
                                'target' => $nomor,
                                'message' => $pesan,
                            ]
                        );

                        $loan->wa_lunas_terkirim = true;
                        $loan->save();
                    } catch (\Exception $e) {

                        \Log::error('Fonnte Error Pembayaran', [
                            'loan_id' => $loan->id,
                            'message' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        // ===================================================
        // PENDING
        // ===================================================
        if ($transaction === 'pending') {

            $loan->status = 'denda';
            $loan->save();
        }

        // ===================================================
        // GAGAL
        // ===================================================
        if (
            in_array(
                $transaction,
                ['deny', 'expire', 'cancel']
            )
        ) {

            $loan->status = 'denda';
            $loan->save();
        }

        return response()->json([
            'success' => true
        ]);
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
        $loan = Loan::with(['user.profile', 'book'])
            ->findOrFail($id);

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

        // ==========================
        // KIRIM WA HANYA 1 KALI
        // ==========================
        if (!$loan->wa_denda_terkirim) {

            $nomor = $loan->user->profile->nomor_hp ?? null;

            if ($nomor) {

                // ubah 08xxxx menjadi 628xxxx
                $nomor = preg_replace('/^0/', '62', $nomor);

                $nama = $loan->user->name;

                $nominalDenda = number_format(
                    $loan->denda,
                    0,
                    ',',
                    '.'
                );

                $pesan =
                    "Halo {$nama} 📚\n\n" .

                    "Anda telah terkena denda keterlambatan pengembalian buku.\n\n" .

                    "Judul Buku : {$loan->book->judul}\n" .
                    "Kode Buku : {$loan->kode_eksemplar}\n" .
                    "Total Denda Saat Ini : Rp {$nominalDenda}\n\n" .

                    "Denda akan terus bertambah Rp 1.000 per hari apabila belum dilunasi.\n\n" .

                    "Konsekuensinya Anda tidak dapat melakukan peminjaman buku baru sampai denda dilunasi.\n\n" .

                    "Silakan login ke Sistem Perpustakaan untuk melihat rincian denda Anda.\n\n" .

                    "Terima kasih 🙏";

                try {

                    Http::withHeaders([
                        'Authorization' => config('services.fonnte.token')
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $nomor,
                        'message' => $pesan,
                    ]);

                    $loan->wa_denda_terkirim = true;
                    $loan->save();
                } catch (\Exception $e) {

                    \Log::error('Fonnte Error', [
                        'loan_id' => $loan->id,
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }

        return redirect('/mahasiswa/denda')
            ->with('success', 'Denda diaktifkan dan notifikasi WA berhasil dikirim');
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

    public function cetakPdf()
    {
        $loans = Loan::where('status', 'lunas')
            ->with(['book', 'user'])
            ->latest()
            ->get();

        $totalDendaLunas = $loans->sum('denda_dibayar');

        $pdf = Pdf::loadView(
            'staff.laporan-keuangan-pdf',
            compact(
                'loans',
                'totalDendaLunas'
            )
        );

        return $pdf->download(
            'laporan-keuangan.pdf'
        );
    }

    public function exportExcel()
    {
        return Excel::download(
            new LaporanKeuanganExport,
            'laporan-keuangan.xlsx'
        );
    }

    public function kembalikan($id)
    {
        $loan = Loan::findOrFail($id);

        // hanya boleh milik sendiri
        if ($loan->user_id != auth()->id()) {

            return back()->with(
                'error',
                'Akses ditolak'
            );
        }

        // hanya yang masih dipinjam
        if ($loan->status !== 'dipinjam') {

            return back()->with(
                'error',
                'Buku tidak dapat dikembalikan'
            );
        }

        $loan->status = 'kembali';

        $loan->save();

        return back()->with(
            'success',
            'Buku berhasil dikembalikan'
        );
    }

    public function pengembalian()
    {
        return view('mahasiswa.pengembalian');
    }

    public function cariPengembalian(Request $request)
    {
        $request->validate([
            'kode' => 'required'
        ]);

        $loan = Loan::with('book')
            ->where('kode_eksemplar', $request->kode)
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$loan) {

            return back()->with(
                'error',
                'Data pinjaman tidak ditemukan'
            );
        }

        // =====================================
        // CEK DENDA
        // =====================================

        if ($loan->status === 'denda') {

            return back()->with(
                'error',
                'Anda terkena denda, silakan lunasi denda terlebih dahulu. Terima kasih.'
            );
        }

        // =====================================
        // HANYA BOLEH STATUS DIPINJAM
        // =====================================

        if ($loan->status === 'kembali') {

            return back()->with(
                'error',
                'Buku ini sudah pernah dikembalikan.'
            );
        }

        if ($loan->status === 'lunas') {

            return back()->with(
                'error',
                'Data pinjaman ini sudah selesai.'
            );
        }

        if ($loan->status === 'denda') {

            return back()->with(
                'error',
                'Anda terkena denda, silakan lunasi denda terlebih dahulu agar dapat menggunakan layanan perpustakaan kembali.'
            );
        }

        // =====================================
        // CEK SUDAH LEWAT JATUH TEMPO
        // =====================================

        $today = now()->startOfDay();

        $jatuhTempo = \Carbon\Carbon::parse(
            $loan->tanggal_kembali
        )->startOfDay();

        if ($today->gt($jatuhTempo)) {

            return back()->with(
                'error',
                'Buku tidak dapat dikembalikan karena Anda terkena denda. Silakan aktivasi dan lunasi denda terlebih dahulu agar dapat menggunakan layanan perpustakaan kembali.'
            );
        }

        return view(
            'mahasiswa.pengembalian',
            compact('loan')
        );
    }

    public function konfirmasiPengembalian($id)
    {
        $loan = Loan::with([
            'user.profile',
            'book'
        ])->findOrFail($id);

        if ($loan->user_id != auth()->id()) {

            return back()->with(
                'error',
                'Akses ditolak'
            );
        }

        if ($loan->status !== 'dipinjam') {

            return back()->with(
                'error',
                'Buku tidak dapat dikembalikan'
            );
        }

        $loan->status = 'kembali';

        $loan->save();

        // =====================================
        // KIRIM WA PENGEMBALIAN BERHASIL
        // =====================================

        $nomor = $loan->user->profile->nomor_hp ?? null;

        if ($nomor) {

            $nomor = preg_replace('/^0/', '62', $nomor);

            $nama = $loan->user->name;

            $tanggalPengembalian =
                now()->format('d-m-Y H:i');

            $pesan =
                "Halo {$nama} 📚\n\n" .

                "Pengembalian buku berhasil.\n\n" .

                "Judul Buku : {$loan->book->judul}\n" .
                "Kode Buku : {$loan->kode_eksemplar}\n\n" .

                "Tanggal Pengembalian : {$tanggalPengembalian}\n\n" .

                "Status : DIKEMBALIKAN ✅\n\n" .

                "Terima kasih telah menggunakan layanan perpustakaan.\n\n" .

                "Perpustakaan Digital";

            try {

                Http::withHeaders([
                    'Authorization' => config('services.fonnte.token')
                ])->post(
                    'https://api.fonnte.com/send',
                    [
                        'target' => $nomor,
                        'message' => $pesan,
                    ]
                );
            } catch (\Exception $e) {

                \Log::error('WA Pengembalian Gagal', [
                    'loan_id' => $loan->id,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return redirect('/mahasiswa/pengembalian')
            ->with(
                'success',
                'Buku berhasil dikembalikan'
            );
    }
}
