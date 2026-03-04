@extends('layouts.staff')

@section('content')

<h2>Bibliografi</h2>

<a href="/books/create">Tambah Bibliografi Baru</a>

<br><br>

<form method="POST" action="/books/delete-selected">

@csrf

<button type="submit">Hapus Data Terpilih</button>
<button type="button" onclick="selectAll()">Tandai Semua</button>
<button type="button" onclick="unselectAll()">Hilangkan Semua Tanda</button>

<br><br>

<table width="100%" cellpadding="10">

<tr style="border-bottom:1px solid #ddd;">
<th>HAPUS</th>
<th>SUNTING</th>
<th>JUDUL</th>
<th>ISBN/ISSN</th>
</tr>

@foreach($books as $book)

<tr style="border-bottom:1px solid #ddd;">

<td>
<input type="checkbox" name="ids[]" value="{{ $book->id }}">
</td>

<td>

<form action="/books/{{ $book->id }}" method="POST">

@csrf
@method('DELETE')

<button type="submit">✏️</button>

</form>

</td>

<td>

<div style="display:flex;gap:15px;align-items:center;">

@if($book->gambar)

<img src="{{ asset('storage/'.$book->gambar) }}" width="60">

@else

<img src="https://via.placeholder.com/60x80">

@endif

<div>

<b>{{ $book->judul }}</b>

<br>

<span style="color:gray;">
{{ $book->pengarang }}
</span>

<br>

<span style="color:#888;font-size:13px;">
{{ $book->tempat_terbit }} - {{ $book->tahun_terbit }}
</span>

</div>

</div>

</td>

<td>

{{ $book->isbn_issn }}

</td>

</tr>

@endforeach

</table>

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
