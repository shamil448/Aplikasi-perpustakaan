<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Perpustakaan</title>

    <style>

        *{
            box-sizing:border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            overflow: hidden;

            background: linear-gradient(
                -45deg,
                #4f46e5,
                #06b6d4,
                #3b82f6,
                #7c3aed
            );

            background-size: 400% 400%;

            animation: gradientMove 12s ease infinite;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        @keyframes gradientMove {

            0%{
                background-position:0% 50%;
            }

            50%{
                background-position:100% 50%;
            }

            100%{
                background-position:0% 50%;
            }
        }

        /* =======================
           BLOB ANIMATION
        ======================= */

        .blob{
            position:absolute;
            border-radius:50%;
            filter:blur(60px);
            opacity:.4;
        }

        .blob1{
            width:300px;
            height:300px;
            background:white;
            top:-50px;
            left:-50px;
            animation:float1 12s infinite ease-in-out;
        }

        .blob2{
            width:350px;
            height:350px;
            background:#60a5fa;
            bottom:-80px;
            right:-80px;
            animation:float2 15s infinite ease-in-out;
        }

        .blob3{
            width:250px;
            height:250px;
            background:#a78bfa;
            top:40%;
            left:60%;
            animation:float3 18s infinite ease-in-out;
        }

        @keyframes float1{
            50%{
                transform:translate(100px,80px);
            }
        }

        @keyframes float2{
            50%{
                transform:translate(-120px,-70px);
            }
        }

        @keyframes float3{
            50%{
                transform:translate(-100px,100px);
            }
        }

        /* =======================
           LOGIN CARD
        ======================= */

        .login-card {

            position: relative;
            z-index: 10;

            width: 360px;

            padding: 40px;

            border-radius: 20px;

            background: rgba(255,255,255,.15);

            backdrop-filter: blur(20px);

            border: 1px solid rgba(255,255,255,.25);

            box-shadow:
                0 20px 40px rgba(0,0,0,.15);
        }

        .login-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group input {

            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 10px;

            font-size: 14px;

            background: rgba(255,255,255,.9);
        }

        .input-group input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,255,255,.3);
        }

        .login-btn {

            width: 100%;

            padding: 12px;

            border: none;

            border-radius: 10px;

            background: #4f46e5;

            color: white;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;

            transition: .3s;
        }

        .login-btn:hover {

            background: #4338ca;

            transform: translateY(-2px);
        }

        .login-link {

            text-align: center;

            margin-top: 18px;

            color: white;

            font-size: 13px;
        }

        .login-link a {

            color: white;

            font-weight: 700;

            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .footer-text {

            text-align: center;

            margin-top: 18px;

            color: rgba(255,255,255,.9);

            font-size: 12px;
        }

        .alert-success {

            background: #dcfce7;

            color: #166534;

            padding: 10px;

            border-radius: 8px;

            margin-bottom: 15px;

            font-size: 13px;
        }

        .alert-error {

            background: #fee2e2;

            color: #991b1b;

            padding: 10px;

            border-radius: 8px;

            margin-bottom: 15px;

            font-size: 13px;
        }

    </style>

</head>

<body>

    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="blob blob3"></div>

    <div class="login-card">

        <div class="login-title">
            Login Sistem Perpustakaan
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="input-group">
                <input
                    type="text"
                    name="username"
                    placeholder="Username"
                    value="{{ old('username') }}"
                    required>
            </div>

            <div class="input-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Kata Sandi"
                    required>
            </div>

            <button type="submit" class="login-btn">
                Login
            </button>

        </form>

        <div class="login-link">
            Belum punya akun?
            <a href="/register">
                Silakan Register
            </a>
        </div>

        <div class="footer-text">
            © Perpustakaan Digital
        </div>

    </div>

</body>

</html>
