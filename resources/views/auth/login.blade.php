<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Perpustakaan</title>

<style>

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:linear-gradient(135deg,#4f46e5,#06b6d4);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-card{
    background:white;
    padding:40px;
    border-radius:12px;
    width:320px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.login-title{
    text-align:center;
    font-size:22px;
    font-weight:600;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:15px;
}

.input-group input{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
    font-size:14px;
}

.input-group input:focus{
    outline:none;
    border-color:#4f46e5;
}

.login-btn{
    width:100%;
    padding:10px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
    transition:0.2s;
}

.login-btn:hover{
    background:#4338ca;
}

.footer-text{
    text-align:center;
    margin-top:15px;
    font-size:12px;
    color:#888;
}

</style>

</head>

<body>

<div class="login-card">

<div class="login-title">
Login Sistem Perpustakaan
</div>

<form method="POST" action="/login">
@csrf

<div class="input-group">
<input type="text" name="name" placeholder="Nama" required>
</div>

<div class="input-group">
<input type="password" name="password" placeholder="Password" required>
</div>

<button type="submit" class="login-btn">
Login
</button>

</form>

<div class="footer-text">
© Perpustakaan Digital
</div>

</div>

</body>
</html>
