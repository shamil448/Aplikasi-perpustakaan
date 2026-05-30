<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKeuanganExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Loan::where('status', 'lunas')
            ->with(['user', 'book'])
            ->get()
            ->map(function ($loan) {

                return [

                    'Nama Peminjam' => $loan->user->name,

                    'Judul Buku' => $loan->book->judul,

                    'Tanggal Pinjam' =>
                        $loan->tanggal_pinjam
                            ? $loan->tanggal_pinjam->format('d-m-Y')
                            : '-',

                    'Tanggal Kembali' =>
                        $loan->tanggal_kembali
                            ? $loan->tanggal_kembali->format('d-m-Y')
                            : '-',

                    'Tanggal Bayar' =>
                        $loan->tanggal_bayar
                            ? $loan->tanggal_bayar->format('d-m-Y H:i')
                            : '-',

                    'Denda Dibayar' =>
                        $loan->denda_dibayar,

                    'Status' =>
                        'Lunas'
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Tanggal Bayar',
            'Denda Dibayar',
            'Status'
        ];
    }
}
