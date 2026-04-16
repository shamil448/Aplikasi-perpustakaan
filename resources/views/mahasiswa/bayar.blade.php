@extends('layouts.mahasiswa')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }

    .card {
        max-width: 420px;
        margin: 80px auto;
        background: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
    }

    .btn-bayar {
        background: #2563eb;
        color: white;
        padding: 12px;
        border-radius: 10px;
        display: block;
        text-decoration: none;
        margin-top: 20px;
    }
</style>

<div class="card">

    <h2>Pembayaran Denda</h2>

    <p>ID Anggota: {{ auth()->user()->name }}</p>

    <p>Status: <b style="color:orange;">Menunggu Pembayaran</b></p>

    <p>Sisa Waktu: <span id="timer" style="color:red;">15:00</span></p>

    <hr>

    <h3>Total Denda</h3>
    <h2 style="color:#2563eb;">Rp {{ number_format($denda) }}</h2>

    <a href="#" class="btn-bayar">Bayar via QRIS</a>

</div>

<script>
    let time = 900;

    setInterval(() => {
        let minutes = Math.floor(time / 60);
        let seconds = time % 60;

        document.getElementById('timer').innerText =
            minutes + ":" + (seconds < 10 ? '0' : '') + seconds;

        time--;
    }, 1000);
</script>

@endsection
