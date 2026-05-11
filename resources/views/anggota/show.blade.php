@extends('layouts.staff')

@section('content')

<style>
    .biodata-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        max-width: 800px;
    }

    .biodata-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }

    .readonly {
        background: #f3f4f6;
    }
</style>

<div class="biodata-card">

    <div class="biodata-title">
        Biodata Anggota
    </div>

    <form method="POST" action="/anggota/{{ $member->id }}/profile">

        @csrf

        {{-- NAMA --}}
        <div class="form-group">

            <label class="form-label">
                Nama
            </label>

            <input
                type="text"
                class="form-input readonly"
                value="{{ $member->name }}"
                readonly>

        </div>

        {{-- EMAIL --}}
        <div class="form-group">

            <label class="form-label">
                Email
            </label>

            <input
                type="email"
                class="form-input readonly"
                value="{{ $member->email }}"
                readonly>

        </div>

        {{-- PASSWORD --}}
        <div class="form-group">

            <label class="form-label">
                Password
            </label>

            <input
                type="password"
                class="form-input readonly"
                value="password"
                readonly>

        </div>

        {{-- ROLE --}}
        <div class="form-group">

            <label class="form-label">
                Role
            </label>

            <input
                type="text"
                class="form-input readonly"
                value="{{ ucfirst($member->role) }}"
                readonly>

        </div>

        <hr style="margin:30px 0;">

        {{-- INPUT MANUAL --}}

        <div class="form-group">

            <label class="form-label">
                NIM / NIDN
            </label>

            <input
                type="text"
                name="nim_nidn"
                class="form-input"
                value="{{ $member->profile->nim_nidn ?? '' }}"
                placeholder="Masukkan NIM atau NIDN">

        </div>

        <div class="form-group">

            <label class="form-label">
                Fakultas
            </label>

            <input
                type="text"
                name="fakultas"
                class="form-input"
                value="{{ $member->profile->fakultas ?? '' }}">

        </div>

        <div class="form-group">

            <label class="form-label">
                Jurusan
            </label>

            <input
                type="text"
                name="jurusan"
                class="form-input"
                value="{{ $member->profile->jurusan ?? '' }}">

        </div>

        <div class="form-group">

            <label class="form-label">
                Nomor HP
            </label>

            <input
                type="text"
                name="nomor_hp"
                class="form-input"
                value="{{ $member->profile->nomor_hp ?? '' }}">

        </div>

        <div class="form-group">

            <label class="form-label">
                Alamat
            </label>

            <textarea
                name="alamat"
                class="form-input"
                rows="4">{{ $member->profile->alamat ?? '' }}</textarea>

        </div>

        <div style="display:flex; gap:10px; margin-top:25px;">

            {{-- tombol kembali --}}
            <a href="/anggota" class="btn btn-secondary">
                ← Kembali
            </a>

            {{-- tombol update --}}
            <button type="submit" class="btn btn-primary">
                💾 Update Biodata
            </button>

        </div>

    </form>

</div>

@endsection
