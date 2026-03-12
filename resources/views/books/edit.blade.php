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

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 4px rgba(37, 99, 235, 0.3);
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

    .current-cover {
        margin-bottom: 10px;
    }

    .section-title {
        margin-top: 25px;
        font-weight: 600;
        color: #333;
    }
</style>


<div class="page-title">
    Edit Buku
</div>

<div class="card">

    <form method="POST" action="/books/{{ $book->id }}" enctype="multipart/form-data">

        @csrf
        @method('PUT')


        {{-- ========================= --}}
        {{-- DATA BIBLIOGRAFI --}}
        {{-- ========================= --}}

        <div class="section-title">
            Data Bibliografi
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" value="{{ $book->judul }}" required>
        </div>

        <div class="form-group">
            <label>Pengarang</label>
            <input type="text" name="pengarang" value="{{ $book->pengarang }}" required>
        </div>

        <div class="form-group">
            <label>Edisi Buku</label>
            <input type="text" name="edisi" value="{{ $book->edisi }}">
        </div>

        <div class="form-group">
            <label>ISBN / ISSN</label>
            <input type="text" name="isbn_issn" value="{{ $book->isbn_issn }}">
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" value="{{ $book->tahun_terbit }}">
        </div>

        <div class="form-group">
            <label>Tempat Terbit</label>
            <input type="text" name="tempat_terbit" value="{{ $book->tempat_terbit }}">
        </div>

        <div class="form-group">
            <label>Deskripsi Fisik</label>
            <input type="text" name="deskripsi_fisik" value="{{ $book->deskripsi_fisik }}">
        </div>

        <div class="form-group">
            <label>Bahasa Buku</label>
            <input type="text" name="bahasa" value="{{ $book->bahasa }}">
        </div>


        {{-- ========================= --}}
        {{-- DATA INVENTARIS --}}
        {{-- ========================= --}}

        <div class="section-title">
            Data Inventaris Buku
        </div>

        <div class="form-group">
            <label>No Panggil</label>
            <input type="text" name="no_panggil" value="{{ $book->no_panggil }}">
        </div>

        <div class="form-group">
            <label>Kode Inventaris</label>
            <input type="text" name="kode_inventaris" value="{{ $book->kode_inventaris }}">
        </div>

        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="lokasi" value="{{ $book->lokasi }}">
        </div>

        <div class="form-group">
            <label>Lokasi Rak</label>
            <input type="text" name="lokasi_rak" value="{{ $book->lokasi_rak }}">
        </div>

        <div class="form-group">
            <label>Kode Eksemplar</label>
            <input type="text" name="eksemplar"
                value="{{ $book->eksemplar }}"
                placeholder="Contoh: MBR-001">
        </div>


        {{-- ========================= --}}
        {{-- GAMBAR BUKU --}}
        {{-- ========================= --}}

        <div class="section-title">
            Cover Buku
        </div>

        <div class="form-group">

            <label>Gambar Buku</label>

            @if($book->gambar)

            <div class="current-cover">
                <img src="{{ asset('storage/'.$book->gambar) }}" width="90">
            </div>

            @endif

            <input type="file" name="gambar" onchange="previewImage(event)">

            <div class="preview" id="preview"></div>

        </div>


        <button class="btn btn-primary" type="submit">
            Update Buku
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
