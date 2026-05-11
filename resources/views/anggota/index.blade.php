@extends('layouts.staff')

@section('content')

<style>

    .member-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:25px;
    }

    .member-title{
        font-size:28px;
        font-weight:bold;
    }

    .member-buttons{
        display:flex;
        gap:10px;
    }

    .search-box{
        display:flex;
        gap:10px;
        align-items:center;
        margin-bottom:20px;
    }

    .search-input{
        width:320px;
        padding:10px;
        border:1px solid #ccc;
        border-radius:6px;
    }

    .table-card{
        background:white;
        border-radius:10px;
        overflow:hidden;
        box-shadow:0 4px 10px rgba(0,0,0,0.05);
    }

    .member-table{
        width:100%;
        border-collapse:collapse;
    }

    .member-table th{
        background:#f9fafb;
        padding:15px;
        text-align:left;
        font-size:13px;
        border-bottom:1px solid #eee;
    }

    .member-table td{
        padding:18px 15px;
        border-bottom:1px solid #f1f1f1;
    }

    .member-info{
        display:flex;
        align-items:center;
        gap:15px;
    }

    .member-avatar{
        width:55px;
        height:55px;
        border-radius:50%;
        background:#eee;
    }

    .member-name{
        font-weight:600;
    }

    .member-role{
        font-size:13px;
        color:#777;
    }

    .badge{
        background:#2563eb;
        color:white;
        padding:5px 12px;
        border-radius:30px;
        font-size:12px;
    }

</style>

<div class="member-header">

    <div class="member-title">
        Keanggotaan
    </div>

    <div class="member-buttons">

        <a href="/anggota" class="btn btn-gray">
            Daftar Anggota
        </a>

        <a href="#" class="btn btn-primary">
            Tambah Anggota
        </a>

        <a href="#" class="btn btn-danger">
            Lihat Anggota Kedaluwarsa
        </a>

    </div>

</div>

<form method="GET" action="/anggota">

    <div class="search-box">

        <label>Cari</label>

        <input
            type="text"
            name="search"
            class="search-input"
            placeholder="Cari anggota..."
            value="{{ request('search') }}">

        <button class="btn btn-gray">
            Cari
        </button>

    </div>

</form>

<div style="margin-bottom:15px; display:flex; gap:10px;">

    <button type="button" class="btn btn-danger">
        Hapus Data Terpilih
    </button>

    <button type="button" onclick="selectAll()" class="btn btn-secondary">
        Tandai Semua
    </button>

    <button type="button" onclick="unselectAll()" class="btn btn-secondary">
        Hilangkan Semua
    </button>

</div>

<div class="table-card">

    <table class="member-table">

        <tr>
            <th width="50">HAPUS</th>
            <th width="100">SUNTING</th>
            <th>ID</th>
            <th>NAMA ANGGOTA</th>
            <th>TIPE</th>
            <th>SUREL</th>
            <th>TERAKHIR DIUBAH</th>
        </tr>

        @foreach($members as $member)

        <tr>

            <td>
                <input type="checkbox" class="member-checkbox">
            </td>

            <td>

                <a href="/anggota/{{ $member->id }}" class="btn btn-secondary">
                    ✏️
                </a>

            </td>

            <td>
                {{ $member->id }}
            </td>

            <td>

                <div class="member-info">

                    <img
                        src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                        class="member-avatar">

                    <div>

                        <div class="member-name">
                            {{ $member->name }}
                        </div>

                        <div class="member-role">
                            {{ ucfirst($member->role) }}
                        </div>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge">
                    Standard
                </span>

            </td>

            <td>
                {{ $member->email }}
            </td>

            <td>
                {{ $member->updated_at->format('Y-m-d') }}
            </td>

        </tr>

        @endforeach

    </table>

</div>

<script>

    function selectAll() {

        document.querySelectorAll('.member-checkbox')
            .forEach(cb => cb.checked = true);
    }

    function unselectAll() {

        document.querySelectorAll('.member-checkbox')
            .forEach(cb => cb.checked = false);
    }

</script>

@endsection
