@extends('layouts.mahasiswa')

@section('content')

<style>

.wrapper{
display:flex;
gap:30px;
}

/* SIDEBAR */

.sirkulasi{
width:320px;
background:linear-gradient(180deg,#1e40af,#2563eb);
padding:30px;
border-radius:15px;
color:white;
}

.sirkulasi h3{
margin-bottom:20px;
font-size:14px;
opacity:0.8;
}

.menu{
display:flex;
flex-direction:column;
gap:14px;
}

.menu a{
text-decoration:none;
color:white;
font-size:18px;
padding:12px 18px;
border-radius:30px;
}

.menu a.active{
background:white;
color:#2563eb;
font-weight:600;
}

/* KONTEN KANAN */

.transaksi{
flex:1;
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

/* TAB */

.tabs{
display:flex;
gap:25px;
border-bottom:1px solid #ddd;
padding-bottom:10px;
margin-bottom:25px;
}

.tabs a{
text-decoration:none;
color:#444;
font-weight:500;
}

.tabs a.active{
color:#2563eb;
font-weight:600;
}

/* FORM */

.form-pinjam{
display:flex;
align-items:center;
gap:10px;
}

.form-pinjam input{
padding:10px;
width:400px;
border:1px solid #ccc;
border-radius:6px;
}

.form-pinjam button{
background:#6b7280;
color:white;
border:none;
padding:10px 20px;
border-radius:6px;
cursor:pointer;
}

</style>

<div class="wrapper">

<!-- MENU BIRU -->

<div class="sirkulasi">

<h3>SIRKULASI</h3>

<div class="menu">

<a class="active">Mulai Transaksi</a>
<a>Pengembalian Kilat</a>
<a>Aturan Peminjaman</a>
<a>Sejarah Peminjaman</a>
<a>Peringatan Jatuh Tempo</a>
<a>Daftar Keterlambatan</a>
<a>Reservasi</a>

</div>

</div>


<!-- HALAMAN TRANSAKSI -->

<div class="transaksi">

<div class="tabs">

<a class="active">Peminjaman (F2)</a>
<a>Pinjaman Saat Ini (F3)</a>
<a>Reservasi (F4)</a>
<a>Denda (F9)</a>
<a>Sejarah Peminjaman (F10)</a>

</div>

<div class="form-pinjam">

<label>Masukkan Kode Eksemplar/Barkod</label>

<input type="text">

<button>Pinjam</button>

</div>

</div>

</div>

@endsection
