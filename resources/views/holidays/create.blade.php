@extends('layouts.staff')

@section('content')

<div class="page-header">

    <div class="page-title">
        Tambah Hari Libur
    </div>

</div>

<div class="card">

    <form method="POST" action="/holidays">

        @csrf

        <div style="margin-bottom:20px;">

            <label>Tanggal</label><br>

            <input
                type="date"
                name="tanggal"
                value="{{ old('tanggal') }}"
                required
                style="width:300px;padding:8px;">

        </div>

        <div style="margin-bottom:20px;">

            <label>Nama Hari Libur</label><br>

            <input
                type="text"
                name="nama_libur"
                value="{{ old('nama_libur') }}"
                required
                style="width:400px;padding:8px;">

        </div>

        <button class="btn btn-primary">
            Simpan
        </button>

        <a href="/holidays" class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

@endsection