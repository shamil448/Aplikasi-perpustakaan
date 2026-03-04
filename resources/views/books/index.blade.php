@extends('layouts.staff')

@section('content')

<style>

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.page-title{
font-size:22px;
font-weight:600;
}

.top-buttons{
display:flex;
gap:10px;
}

.search-bar{
display:flex;
align-items:center;
gap:10px;
margin-bottom:20px;
}

.search-input{
padding:8px;
border:1px solid #ccc;
border-radius:4px;
width:320px;
}

.search-select{
padding:8px;
border:1px solid #ccc;
border-radius:4px;
}

.btn{
padding:8px 14px;
border:none;
border-radius:6px;
cursor:pointer;
font-size:14px;
}

.btn-primary{
background:#2563eb;
color:white;
}

.btn-danger{
background:#ef4444;
color:white;
}

.btn-secondary{
background:#e5e7eb;
}

.btn-gray{
background:#777;
color:white;
}

.btn-blue{
background:#1da1b9;
color:white;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

table{
width:100%;
border-collapse:collapse;
}

th{
text-align:left;
font-size:13px;
color:#666;
padding:12px;
border-bottom:1px solid #eee;
}

td{
padding:14px 12px;
border-bottom:1px solid #f1f1f1;
}

tr:hover{
background:#fafafa;
}

.book-cover{
width:60px;
height:80px;
object-fit:cover;
border-radius:5px;
}

.book-title{
font-weight:600;
}

.book-author{
color:#666;
font-size:14px;
}

.book-meta{
font-size:12px;
color:#999;
}

</style>


<!-- HEADER -->

<div class="page-header">

<div class="page-title">
Bibliografi
</div>

<div class="top-buttons">
<a href="/books" class="btn btn-gray">Daftar Bibliografi</a>
<a href="/books/create" class="btn btn-gray">Tambah Bibliografi Baru</a>
</div>

</div>


<!-- SEARCH BAR -->

<div class="search-bar">

<label>Cari</label>

<input class="search-input" type="text" placeholder="Cari buku...">

<select class="search-select">
<option>Semua Ruas</option>
<option>Judul</option>
<option>Pengarang</option>
<option>ISBN</option>
</select>

<button class="btn btn-gray">
Cari
</button>

<button class="btn btn-blue">
Pencarian Spesifik
</button>

</div>


<form method="POST" action="/books/delete-selected">

@csrf

<div style="margin-bottom:15px; display:flex; gap:10px;">

<button type="submit" class="btn btn-danger">
Hapus Terpilih
</button>

<button type="button" onclick="selectAll()" class="btn btn-secondary">
Tandai Semua
</button>

<button type="button" onclick="unselectAll()" class="btn btn-secondary">
Hilangkan Semua
</button>

</div>


<div class="card">

<table>

<tr>
<th width="40">HAPUS</th>
<th width="60">SUNTING</th>
<th>JUDUL</th>
<th width="150">ISBN / ISSN</th>
</tr>

@foreach($books as $book)

<tr>

<td>
<input type="checkbox" name="ids[]" value="{{ $book->id }}">
</td>

<td>

<form action="/books/{{ $book->id }}" method="POST">

@csrf
@method('DELETE')

<button class="btn btn-secondary" type="submit">
✏️
</button>

</form>

</td>

<td>

<div style="display:flex;gap:15px;align-items:center;">

@if($book->gambar)

<img class="book-cover" src="{{ asset('storage/'.$book->gambar) }}">

@else

<img class="book-cover" src="https://via.placeholder.com/60x80">

@endif

<div>

<div class="book-title">
{{ $book->judul }}
</div>

<div class="book-author">
{{ $book->pengarang }}
</div>

<div class="book-meta">
{{ $book->tempat_terbit }} - {{ $book->tahun_terbit }}
</div>

</div>

</div>

</td>

<td>
{{ $book->isbn_issn }}
</td>

</tr>

@endforeach

</table>

</div>

</form>


<script>

function selectAll(){
document.querySelectorAll('input[type=checkbox]').forEach(cb=>cb.checked=true);
}

function unselectAll(){
document.querySelectorAll('input[type=checkbox]').forEach(cb=>cb.checked=false);
}

</script>

@endsection
