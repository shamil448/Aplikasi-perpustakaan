@extends('layouts.staff')

@section('content')

<style>

.dashboard-title{
    font-size:28px;
    font-weight:700;
    margin-bottom:25px;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.card{
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.card h3{
    font-size:14px;
    color:#666;
    margin-bottom:10px;
}

.card .value{
    font-size:30px;
    font-weight:700;
    color:#2563eb;
}

.card.green .value{
    color:#16a34a;
}

.card.red .value{
    color:#dc2626;
}

.card.orange .value{
    color:#ea580c;
}

.chart-grid{
    margin-top:40px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
    gap:20px;
}

.chart-card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.chart-card h3{
    margin-bottom:15px;
    font-size:16px;
    font-weight:600;
}

.logout-box{
    margin-top:30px;
}

.logout-btn{
    background:#dc2626;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}

.logout-btn:hover{
    opacity:.9;
}

</style>

<div class="dashboard-title">
    Dashboard Staff
</div>

<div class="cards">

    <div class="card">
        <h3>Total Buku</h3>
        <div class="value">
            {{ number_format($totalBuku) }}
        </div>
    </div>

    <div class="card green">
        <h3>Total Anggota</h3>
        <div class="value">
            {{ number_format($totalAnggota) }}
        </div>
    </div>

    <div class="card">
        <h3>Buku Sedang Dipinjam</h3>
        <div class="value">
            {{ number_format($bukuDipinjam) }}
        </div>
    </div>

    <div class="card red">
        <h3>Total Denda Aktif</h3>
        <div class="value">
            Rp {{ number_format($totalDendaAktif, 0, ',', '.') }}
        </div>
    </div>

    <div class="card orange">
        <h3>Total Pendapatan Denda</h3>
        <div class="value">
            Rp {{ number_format($totalPendapatanDenda, 0, ',', '.') }}
        </div>
    </div>

</div>

{{-- ========================= --}}
{{-- DIAGRAM --}}
{{-- ========================= --}}

<div class="chart-grid">

    <div class="chart-card">

        <h3>📈 Laporan Keuangan</h3>

        <canvas id="keuanganChart"></canvas>

    </div>

    <div class="chart-card">

        <h3>📚 Status Peminjaman Buku</h3>

        <canvas id="peminjamChart"></canvas>

    </div>

    <div class="chart-card">

        <h3>💰 Status Denda</h3>

        <canvas id="dendaChart"></canvas>

    </div>

</div>

<div class="logout-box">

    <form method="POST" action="/logout">

        @csrf

        <button
            type="submit"
            class="logout-btn">

            Logout

        </button>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// =====================
// GRAFIK KEUANGAN
// =====================

new Chart(
    document.getElementById('keuanganChart'),
    {
        type: 'bar',
        data: {
            labels: ['Pendapatan Denda'],
            datasets: [{
                label: 'Rupiah',
                data: [{{ $totalPendapatanDenda }}]
            }]
        }
    }
);

// =====================
// GRAFIK STATUS PINJAMAN
// =====================

new Chart(
    document.getElementById('peminjamChart'),
    {
        type: 'doughnut',
        data: {
            labels: [
                'Sedang Dipinjam',
                'Kena Denda',
                'Sudah Lunas'
            ],
            datasets: [{
                data: [
                    {{ $jumlahDipinjam }},
                    {{ $jumlahDenda }},
                    {{ $jumlahLunas }}
                ]
            }]
        }
    }
);

// =====================
// GRAFIK STATUS DENDA
// =====================

new Chart(
    document.getElementById('dendaChart'),
    {
        type: 'pie',
        data: {
            labels: [
                'Belum Denda',
                'Kena Denda',
                'Sudah Lunas'
            ],
            datasets: [{
                data: [
                    {{ $jumlahBelumDenda }},
                    {{ $jumlahKenaDenda }},
                    {{ $jumlahLunas }}
                ]
            }]
        }
    }
);

</script>

@endsection
