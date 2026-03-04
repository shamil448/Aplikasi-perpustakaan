<!DOCTYPE html>
<html>
<head>
<title>Staff Perpustakaan</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f5f5f5;
}

/* NAVBAR */

.navbar{
background:#f1f1f1;
padding:15px 30px;
display:flex;
gap:30px;
border-bottom:1px solid #ddd;
font-weight:500;
}

.navbar a{
text-decoration:none;
color:black;
}

.navbar a.active{
color:#1a73e8;
}

/* LAYOUT */

.container{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:260px;
background:linear-gradient(rgba(13,71,161,0.95),rgba(13,71,161,0.95)),
url('/images/sidebar-bg.jpg');
background-size:cover;
color:white;
min-height:100vh;
padding:20px;
}

/* PROFILE */

.profile{
display:flex;
align-items:center;
gap:10px;
margin-bottom:30px;
}

.profile img{
width:45px;
height:45px;
border-radius:50%;
background:white;
}

/* MENU */

.sidebar h4{
font-size:13px;
margin-top:25px;
opacity:0.7;
}

.sidebar a{
display:block;
padding:8px 10px;
margin:3px 0;
color:white;
text-decoration:none;
border-radius:5px;
font-size:14px;
}

.sidebar a:hover{
background:white;
color:#0d47a1;
}

/* MAIN */

.main{
flex:1;
padding:30px;
}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

<a href="#">Beranda</a>
<a class="active">Bibliografi</a>
<a href="#">Sirkulasi</a>
<a href="#">Keanggotaan</a>
<a href="#">Daftar Terkendali</a>
<a href="#">Inventarisasi</a>
<a href="#">Sistem</a>
<a href="#">Pelaporan</a>
<a href="#">Kendali Terbitan Berseri</a>

<form method="POST" action="/logout" style="margin-left:auto;">
@csrf
<button style="border:none;background:none;font-weight:bold;cursor:pointer;">
Keluar
</button>
</form>

</div>


<div class="container">

<!-- SIDEBAR -->

<div class="sidebar">

<div class="profile">
<img src="https://cdn-icons-png.flaticon.com/512/149/149071.png">
<div>
<b>Admin</b><br>
<small>Pustakawan</small>
</div>
</div>

<h4>BIBLIOGRAFI</h4>
<a href="/books">Daftar Bibliografi</a>
<a href="/books/create">Tambah Bibliografi Baru</a>

<h4>EKSEMPLAR</h4>
<a>Daftar Eksemplar</a>
<a>Daftar Eksemplar Keluar</a>

<h4>SALIN KATALOG</h4>
<a>Layanan MARC SRU</a>
<a>Layanan Z3950 SRU</a>
<a>Layanan P2P</a>

<h4>PERALATAN</h4>
<a>Pencetakan Label</a>
<a>Cetak Barkod Eksemplar</a>
<a>Ekspor Data MARC</a>
<a>Impor Data MARC</a>
<a>Mencetak Katalog</a>

</div>

<!-- CONTENT -->

<div class="main">

@yield('content')

</div>

</div>

</body>
</html>
