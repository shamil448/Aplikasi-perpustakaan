@extends('layouts.staff')

@section('content')

<div class="page-header">

    <div class="page-title">
        Edit Hari Libur
    </div>

</div>

<div class="card">

    <form method="POST" action="/holidays/{{ $holiday->id }}">

        @csrf
        @method('PUT')

        <div style="margin-bottom:20px;">

            <label>Tanggal</label><br>

            <input
                type="date"
                name="tanggal"
                value="{{ $holiday->tanggal->format('Y-m-d') }}"
                required
                style="width:300px;padding:8px;">

        </div>

        <div style="margin-bottom:20px;">

            <label>Nama Hari Libur</label><br>

            <input
                type="text"
                name="nama_libur"
                value="{{ $holiday->nama_libur }}"
                required
                style="width:400px;padding:8px;">

        </div>

        <button class="btn btn-primary">
            Update
        </button>

        <a href="/holidays" class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

@endsection