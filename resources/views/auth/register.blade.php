<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register Perpustakaan</title>

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

.register-card{
    background:white;
    padding:40px;
    border-radius:12px;
    width:340px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.register-title{
    text-align:center;
    font-size:22px;
    font-weight:600;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:15px;
}

.input-group input,
.input-group select{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
    font-size:14px;
}

.input-group input:focus,
.input-group select:focus{
    outline:none;
    border-color:#4f46e5;
}

.register-btn{
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

.register-btn:hover{
    background:#4338ca;
}

.footer-text{
    text-align:center;
    margin-top:15px;
    font-size:12px;
    color:#888;
}

.login-link{
    text-align:center;
    margin-top:15px;
    font-size:13px;
}

.login-link a{
    color:#4f46e5;
    text-decoration:none;
}

</style>

</head>

<body>

<div class="register-card">

<div class="register-title">
Register Akun
</div>

<form method="POST" action="/register">
@csrf

<div class="input-group">
<input type="text" name="name" placeholder="Nama" required>
</div>

<div class="input-group">
<input type="email" name="email" placeholder="Email" required>
</div>

<div class="input-group">
<input type="password" name="password" placeholder="Password" required>
</div>

<div class="input-group">
<select name="role" required>
<option value="">Pilih Role</option>
<option value="staff">Staff</option>
<option value="mahasiswa">Mahasiswa</option>
<option value="dosen">Dosen</option>
</select>
</div>

<button type="submit" class="register-btn">
Register
</button>

</form>

<div class="login-link">
Sudah punya akun? <a href="/login">Login</a>
</div>

<div class="footer-text">
© Sistem Perpustakaan Digital
</div>

</div>

</body>
</html>
