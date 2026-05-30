<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Loan;
use Carbon\Carbon;

class KirimReminderJatuhTempo extends Command
{
    /**
     * Nama command
     */
    protected $signature = 'app:kirim-reminder-jatuh-tempo';

    /**
     * Deskripsi command
     */
    protected $description = 'Mengirim WA pengingat H-1 sebelum jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $besok = Carbon::tomorrow()->startOfDay();

        $loans = Loan::with([
            'user.profile',
            'book'
        ])
        ->where('status', 'dipinjam')
        ->whereDate('tanggal_kembali', $besok)
        ->where('wa_reminder_terkirim', false)
        ->get();

        foreach ($loans as $loan) {

            $nomor = $loan->user->profile->nomor_hp ?? null;

            if (!$nomor) {
                continue;
            }

            // ubah 08xxxx menjadi 628xxxx
            $nomor = preg_replace('/^0/', '62', $nomor);

            $nama = $loan->user->name;

            $tanggalKembali = Carbon::parse(
                $loan->tanggal_kembali
            )->format('d-m-Y');

            $pesan =
                "Halo {$nama} 📚\n\n" .

                "Ini adalah pengingat bahwa masa peminjaman buku Anda akan berakhir besok.\n\n" .

                "Judul Buku : {$loan->book->judul}\n" .
                "Kode Buku : {$loan->kode_eksemplar}\n" .
                "Tanggal Kembali : {$tanggalKembali}\n\n" .

                "Silakan segera mengembalikan buku untuk menghindari denda keterlambatan.\n\n" .

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

                $loan->wa_reminder_terkirim = true;
                $loan->save();

                $this->info(
                    'Reminder terkirim ke: ' .
                    $loan->user->name
                );

            } catch (\Exception $e) {

                \Log::error(
                    'Gagal kirim reminder WA',
                    [
                        'loan_id' => $loan->id,
                        'error' => $e->getMessage()
                    ]
                );
            }
        }

        $this->info('Selesai cek reminder jatuh tempo.');
    }
}
