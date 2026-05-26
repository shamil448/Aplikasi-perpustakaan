@extends('layouts.staff')

@section('content')

<style>
    .page-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        max-width: 750px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #444;
    }

    input,
    select,
    textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 4px rgba(37, 99, 235, 0.3);
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }

    .btn {
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
    }

    .btn-primary:hover {
        background: #1e4ed8;
    }

    .preview {
        margin-top: 10px;
    }

    .preview img {
        width: 90px;
        border-radius: 6px;
    }

    .section-title {
        margin-top: 25px;
        font-weight: 600;
        color: #333;
    }

    .error-box {
        background: #fee2e2;
        color: #991b1b;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 15px;
        font-size: 13px;
    }
</style>


<div class="page-title">
    Tambah Bibliografi Baru
</div>


<div class="card">

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/books" enctype="multipart/form-data">

        @csrf


        {{-- ========================= --}}
        {{-- DATA BIBLIOGRAFI --}}
        {{-- ========================= --}}

        <div class="section-title">
            Data Bibliografi
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input
                type="text"
                name="judul"
                value="{{ old('judul') }}"
                required>
        </div>

        <div class="form-group">
            <label>Kategori Buku</label>

            <select name="kategori" required>
                <option value="">Pilih Kategori</option>

                <option value="novel" {{ old('kategori') == 'novel' ? 'selected' : '' }}>
                    Novel
                </option>

                <option value="komik" {{ old('kategori') == 'komik' ? 'selected' : '' }}>
                    Komik
                </option>

                <option value="buku pembelajaran" {{ old('kategori') == 'buku pembelajaran' ? 'selected' : '' }}>
                    Buku Pembelajaran
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Sinopsis Buku</label>

            <textarea
                name="sinopsis"
                placeholder="Masukkan sinopsis buku">{{ old('sinopsis') }}</textarea>
        </div>

        <div class="form-group">
            <label>Pengarang</label>
            <input
                type="text"
                name="pengarang"
                value="{{ old('pengarang') }}"
                required>
        </div>

        <div class="form-group">
            <label>Edisi Buku</label>
            <input
                type="text"
                name="edisi"
                value="{{ old('edisi') }}">
        </div>

        <div class="form-group">
            <label>ISBN / ISSN</label>
            <input
                type="text"
                name="isbn_issn"
                value="{{ old('isbn_issn') }}">
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input
                type="number"
                name="tahun_terbit"
                value="{{ old('tahun_terbit') }}">
        </div>

        <div class="form-group">
            <label>Tempat Terbit</label>
            <input
                type="text"
                name="tempat_terbit"
                value="{{ old('tempat_terbit') }}">
        </div>

        <div class="form-group">
            <label>Deskripsi Fisik</label>
            <input
                type="text"
                name="deskripsi_fisik"
                value="{{ old('deskripsi_fisik') }}">
        </div>

        <div class="form-group">
            <label>Bahasa Buku</label>
            <input
                type="text"
                name="bahasa"
                value="{{ old('bahasa') }}">
        </div>


        {{-- ========================= --}}
        {{-- DATA INVENTARIS --}}
        {{-- ========================= --}}

        <div class="section-title">
            Data Inventaris Buku
        </div>

        <div class="form-group">
            <label>No Panggil</label>
            <input
                type="text"
                name="no_panggil"
                value="{{ old('no_panggil') }}">
        </div>

        <div class="form-group">
            <label>Kode Inventaris</label>
            <input
                type="text"
                name="kode_inventaris"
                value="{{ old('kode_inventaris') }}">
        </div>

        <div class="form-group">
            <label>Lokasi</label>
            <input
                type="text"
                name="lokasi"
                value="{{ old('lokasi') }}">
        </div>

        <div class="form-group">
            <label>Lokasi Rak</label>
            <input
                type="text"
                name="lokasi_rak"
                value="{{ old('lokasi_rak') }}">
        </div>

        <div class="form-group">
            <label>Kode Eksemplar</label>
            <input
                type="text"
                name="eksemplar"
                value="{{ old('eksemplar') }}"
                placeholder="Contoh: MBR-001">
        </div>


        {{-- ========================= --}}
        {{-- COVER BUKU --}}
        {{-- ========================= --}}

        <div class="section-title">
            Cover Buku
        </div>

        <div class="form-group">

            <label>Gambar Buku</label>

            <input type="file" name="gambar" onchange="previewImage(event)">

            <div class="preview" id="preview"></div>

        </div>


        <button class="btn btn-primary" type="submit">
            Simpan Buku
        </button>

    </form>

</div>


<script>
    function previewImage(event) {

        let preview = document.getElementById('preview');
        preview.innerHTML = '';

        let file = event.target.files[0];

        if (file) {

            let img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            preview.appendChild(img);

        }

    }
</script>

@endsection
