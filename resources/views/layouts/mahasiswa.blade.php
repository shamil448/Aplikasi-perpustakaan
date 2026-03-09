<!DOCTYPE html>
<html>
<head>
<title>Dashboard Mahasiswa</title>

<style>

body{
margin:0;
font-family:Segoe UI, Arial;
background:#f3f4f6;
}

/* HEADER */

.header{
background:white;
padding:18px 30px;
border-bottom:1px solid #e5e7eb;
display:flex;
align-items:center;
justify-content:space-between;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
}

.header-title{
font-size:18px;
font-weight:600;
color:#333;
}

/* LOGOUT */

.logout button{
background:#ef4444;
color:white;
border:none;
padding:8px 16px;
border-radius:6px;
cursor:pointer;
}

/* MAIN AREA */

.main{
padding:40px;
}

/* CARD */

.card{
background:white;
padding:35px;
border-radius:12px;
box-shadow:0 4px 12px rgba(0,0,0,0.08);
text-align:center;
}

/* TITLE */

.title{
font-size:24px;
font-weight:600;
margin-bottom:10px;
}

/* SUBTITLE */

.subtitle{
font-size:14px;
color:#777;
margin-bottom:30px;
}

/* BUTTON */

.touch-button{
background:#2563eb;
color:white;
border:none;
padding:18px 40px;
font-size:20px;
border-radius:10px;
cursor:pointer;
transition:0.3s;
}

.touch-button:hover{
background:#1d4ed8;
}

</style>

</head>

<body>

<div class="header">

<div class="header-title">
Perpustakaan Kampus
</div>

<div class="logout">
<form method="POST" action="/logout">
@csrf
<button>Logout</button>
</form>
</div>

</div>


<div class="main">

@yield('content')

</div>

</body>
</html>
