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
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .btn-bayar {
        background: #2563eb;
        color: white;
        padding: 12px;
        border-radius: 10px;
        display: block;
        margin-top: 20px;
        border: none;
        width: 100%;
        cursor: pointer;
        font-weight: bold;
    }

    .status {
        background: #fde68a;
        padding: 5px 10px;
        border-radius: 10px;
        display: inline-block;
        font-size: 14px;
    }

    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
    }
</style>

<div class="card">

    <h2>Pembayaran Denda</h2>
    <p style="color:gray;">Perpustakaan Digital</p>

    <br>

    {{-- ERROR --}}
    @if(session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
    @endif

    <p>ID Anggota: <b>{{ auth()->user()->name }}</b></p>

    <p>Status: <span class="status">Menunggu Pembayaran</span></p>

    <p>Sisa Waktu: <span id="timer" style="color:red;">15:00</span></p>

    <hr>

    <p>Buku: <b>{{ $loan->book->judul }}</b></p>

    <h3>Total Denda</h3>
    <h2 style="color:#2563eb;">Rp {{ number_format($denda) }}</h2>

    <button id="pay-button" class="btn-bayar">
        Bayar via QRIS
    </button>

    <p style="font-size:12px; color:gray; margin-top:15px;">
        Pembayaran aman menggunakan Midtrans
    </p>

</div>

{{-- MIDTRANS SNAP --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

{{-- TIMER --}}
<script>
    let time = 900;

    let timerInterval = setInterval(() => {
        if (time <= 0) {
            clearInterval(timerInterval);
            document.getElementById('timer').innerText = "Waktu Habis";
            return;
        }

        let minutes = Math.floor(time / 60);
        let seconds = time % 60;

        document.getElementById('timer').innerText =
            minutes + ":" + (seconds < 10 ? '0' : '') + seconds;

        time--;
    }, 1000);
</script>

{{-- MIDTRANS --}}
<script>
    document.getElementById('pay-button').onclick = function() {

        snap.pay('{{ $snapToken }}', {

            onSuccess: function(result) {
                alert("Pembayaran berhasil bro 🔥");

                // 🔥 redirect ke halaman pinjaman (lebih logis)
                window.location.href = "/mahasiswa/pinjaman";
            },

            onPending: function(result) {
                alert("Menunggu pembayaran bro ⏳");
            },

            onError: function(result) {
                alert("Pembayaran gagal bro ❌");
            },

            onClose: function() {
                alert("Kamu menutup popup tanpa menyelesaikan pembayaran");
            }

        });

    };
</script>

@endsection
