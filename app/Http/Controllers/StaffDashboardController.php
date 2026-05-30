<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

class StaffDashboardController extends Controller
{
    public function index()
    {
        // =========================
        // KARTU STATISTIK
        // =========================

        $totalBuku = Book::count();

        $totalAnggota = User::whereIn(
            'role',
            ['mahasiswa', 'dosen']
        )->count();

        $bukuDipinjam = Loan::where(
            'status',
            'dipinjam'
        )->count();

        $totalDendaAktif = Loan::sum('denda');

        $totalPendapatanDenda = Loan::sum(
            'denda_dibayar'
        );

        // =========================
        // GRAFIK STATUS PINJAMAN
        // =========================

        $jumlahDipinjam = Loan::where(
            'status',
            'dipinjam'
        )->count();

        $jumlahDenda = Loan::where(
            'status',
            'denda'
        )->count();

        $jumlahLunas = Loan::where(
            'status',
            'lunas'
        )->count();

        // =========================
        // GRAFIK STATUS DENDA
        // =========================

        $jumlahBelumDenda = Loan::where(
            'status',
            'dipinjam'
        )->count();

        $jumlahKenaDenda = Loan::where(
            'status',
            'denda'
        )->count();

        // =========================
        // KIRIM KE VIEW
        // =========================

        return view(
            'dashboard.staff',
            compact(
                'totalBuku',
                'totalAnggota',
                'bukuDipinjam',
                'totalDendaAktif',
                'totalPendapatanDenda',

                // grafik peminjaman
                'jumlahDipinjam',
                'jumlahDenda',
                'jumlahLunas',

                // grafik denda
                'jumlahBelumDenda',
                'jumlahKenaDenda'
            )
        );
    }
}
