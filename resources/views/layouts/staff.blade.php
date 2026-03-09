<!DOCTYPE html>
<html>
<head>
<title>Staff Perpustakaan</title>

<style>

body{
margin:0;
font-family:Segoe UI,Arial;
background:#f3f4f6;
}

/* NAVBAR */

.navbar{
background:white;
padding:15px 30px;
display:flex;
gap:25px;
align-items:center;
border-bottom:1px solid #e5e7eb;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
}

.navbar a{
text-decoration:none;
color:#555;
font-size:14px;
font-weight:500;
}

.navbar a:hover{
color:#2563eb;
}

.navbar a.active{
color:#2563eb;
font-weight:600;
}

/* LAYOUT */

.container{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:250px;
background:#1e3a8a;
color:white;
min-height:100vh;
padding:25px 18px;
}

/* PROFILE */

.profile{
display:flex;
align-items:center;
gap:12px;
margin-bottom:35px;
}

.profile img{
width:45px;
height:45px;
border-radius:50%;
background:white;
}

.profile b{
font-size:15px;
}

/* MENU TITLE */

.sidebar h4{
font-size:12px;
margin-top:25px;
margin-bottom:8px;
opacity:0.7;
letter-spacing:1px;
}

/* MENU LINK */

.sidebar a{
display:block;
padding:10px 12px;
margin:3px 0;
color:white;
text-decoration:none;
border-radius:6px;
font-size:14px;
transition:0.2s;
}

.sidebar a:hover{
background:rgba(255,255,255,0.15);
}

/* MAIN */

.main{
flex:1;
padding:35px;
}

/* CARD STYLE (buat halaman lain juga) */

.card{
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.06);
}

/* BUTTON */

button{
padding:8px 15px;
border:none;
border-radius:6px;
cursor:pointer;
font-weight:500;
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
<a href="#"></a>
<a href="#"></a>
<a href="#"></a>
<a href="#"></a>
<a href="#"></a>

<form method="POST" action="/logout" style="margin-left:auto;">
@csrf
<button style="background:#ef4444;color:white;">
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
<a href="#">Daftar Eksemplar</a>
<a href="#">Daftar Eksemplar Keluar</a>

<h4></h4>
<a href="#"></a>
<a href="#"></a>
<a href="#"></a>

<h4></h4>
<a href="#"></a>
<a href="#"></a>
<a href="#"></a>
<a href="#"></a>
<a href="#"></a>

</div>

<!-- CONTENT -->

<div class="main">

@yield('content')

</div>

</div>

</body>
</html>
