@extends('layouts.staff')

@section('content')

<div class="page-header">

    <div class="page-title">
        Master Hari Libur
    </div>

    <a href="/holidays/create" class="btn btn-primary">
        + Tambah Hari Libur
    </a>

</div>

@if(session('success'))

<div style="
background:#dcfce7;
padding:12px;
border-radius:8px;
margin-bottom:20px;
color:#166534;
">

    {{ session('success') }}

</div>

@endif

<div class="card">

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Tanggal</th>

                <th>Nama Hari Libur</th>

                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($holidays as $holiday)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $holiday->tanggal->format('d M Y') }}</td>

                <td>{{ $holiday->nama_libur }}</td>

                <td>

                    <a
                        href="/holidays/{{ $holiday->id }}/edit"
                        class="btn btn-primary">

                        Edit

                    </a>

                    <form
                        action="/holidays/{{ $holiday->id }}"
                        method="POST"
                        style="display:inline;">

                        @csrf

                        @method('DELETE')

                        <button
                            class="btn btn-danger"
                            onclick="return confirm('Hapus hari libur?')">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="4">

                    Belum ada data hari libur.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection