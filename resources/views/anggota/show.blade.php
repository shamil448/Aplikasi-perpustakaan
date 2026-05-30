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
        box-sizing: border-box;
    }

    .readonly {
        background: #f3f4f6;
        color: #555;
        cursor: not-allowed;
    }

    .btn {
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: black;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
    }
</style>

<div class="biodata-card">

    <div class="biodata-title">
        Biodata Anggota
    </div>

    @if(session('success'))

        <div class="alert-success">
            {{ session('success') }}
        </div>

    @endif

    <form method="POST" action="/anggota/{{ $member->id }}/profile">

        @csrf

        {{-- NAMA --}}
        <div class="form-group">
            <label class="form-label">Nama</label>

            <input
                type="text"
                class="form-input readonly"
                value="{{ $member->name }}"
                readonly>
        </div>

        {{-- EMAIL --}}
        <div class="form-group">
            <label class="form-label">Email</label>

            <input
                type="email"
                class="form-input readonly"
                value="{{ $member->email }}"
                readonly>
        </div>

        {{-- PASSWORD --}}
        <div class="form-group">
            <label class="form-label">Password</label>

            <input
                type="password"
                class="form-input readonly"
                value="password"
                readonly>
        </div>

        {{-- ROLE --}}
        <div class="form-group">
            <label class="form-label">Role</label>

            <input
                type="text"
                class="form-input readonly"
                value="{{ ucfirst($member->role) }}"
                readonly>
        </div>

        <hr style="margin:30px 0;">

        {{-- NIM / NIP / NIDN --}}
        <div class="form-group">

            <label class="form-label">

                @if($member->role == 'mahasiswa')
                    NIM
                @elseif($member->role == 'staff')
                    NIP
                @else
                    NIDN
                @endif

            </label>

            <input
                type="text"
                class="form-input readonly"
                value="{{ $member->profile->nim_nidn ?? '' }}"
                readonly>

        </div>

        {{-- FAKULTAS --}}
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

        {{-- JURUSAN --}}
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

        {{-- NOMOR HP --}}
        <div class="form-group">

            <label class="form-label">
                Nomor HP
            </label>

            <input
                type="text"
                class="form-input readonly"
                value="{{ $member->profile->nomor_hp ?? '' }}"
                readonly>

        </div>

        {{-- ALAMAT --}}
        <div class="form-group">

            <label class="form-label">
                Alamat
            </label>

            <textarea
                name="alamat"
                class="form-input"
                rows="4">{{ $member->profile->alamat ?? '' }}</textarea>

        </div>

        <div
            style="
                display:flex;
                gap:10px;
                margin-top:25px;
                flex-wrap:wrap;
            ">

            <a href="/anggota" class="btn btn-secondary">
                ← Kembali
            </a>

            <button
                type="submit"
                class="btn btn-primary">

                💾 Update Biodata

            </button>

    </form>

            <form
                method="POST"
                action="/anggota/{{ $member->id }}/reset-password"
                onsubmit="return confirm('Yakin reset password anggota ini?')">

                @csrf

                <button
                    type="submit"
                    class="btn btn-warning">

                    🔑 Reset Password

                </button>

            </form>

        </div>

</div>

@endsection
