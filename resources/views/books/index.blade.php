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
text-decoration:none;
display:inline-block;
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

.action-buttons{
display:flex;
gap:5px;
}

.eksemplar-box{
display:flex;
flex-direction:column;
gap:6px;
}

.eksemplar-label{
font-size:13px;
color:#666;
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

<form method="GET" action="/books">

<div class="search-bar">

<label>Cari</label>

<input
class="search-input"
type="text"
name="search"
placeholder="Cari buku..."
value="{{ request('search') }}"
>

<select class="search-select" name="field">
<option value="all">Semua Ruas</option>
<option value="judul">Judul</option>
<option value="pengarang">Pengarang</option>
<option value="isbn_issn">ISBN</option>
</select>

<button class="btn btn-gray" type="submit">
Cari
</button>

<button class="btn btn-blue" type="button">
Pencarian Spesifik
</button>

</div>

</form>


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
<th width="120">AKSI</th>
<th>JUDUL</th>
<th width="150">ISBN / ISSN</th>
<th width="180">EKSEMPLAR</th>
</tr>

@foreach($books as $book)

<tr>

<td>
<input type="checkbox" class="book-checkbox" name="ids[]" value="{{ $book->id }}">
</td>

<td>

<div class="action-buttons">

<a href="/books/{{ $book->id }}/edit" class="btn btn-secondary">
✏️
</a>

<form action="/books/{{ $book->id }}" method="POST">

@csrf
@method('DELETE')

<button
type="submit"
class="btn btn-danger"
onclick="return confirm('Yakin ingin menghapus buku ini?')"
>
🗑
</button>

</form>

</div>

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


<!-- EKSEMPLAR -->

<td>

<div class="eksemplar-box">

<div class="eksemplar-label">
Kelola Eksemplar
</div>

<a href="/books/{{ $book->id }}/eksemplar" class="btn btn-primary">
Eksemplar
</a>

</div>

</td>

</tr>

@endforeach

</table>

</div>

</form>


<script>

function selectAll(){
document.querySelectorAll('.book-checkbox').forEach(cb=>cb.checked=true);
}

function unselectAll(){
document.querySelectorAll('.book-checkbox').forEach(cb=>cb.checked=false);
}

</script>

@endsection
