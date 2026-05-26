<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Perpustakaan</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 360px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .register-title {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .register-btn {
            width: 100%;
            padding: 10px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.2s;
        }

        .register-btn:hover {
            background: #4338ca;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #888;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }

        .login-link a {
            color: #4f46e5;
            text-decoration: none;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 13px;
        }
    </style>

</head>

<body>

    <div class="register-card">

        <div class="register-title">
            Register Akun
        </div>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf

            <div class="input-group">
                <input
                    type="text"
                    name="name"
                    placeholder="Nama Lengkap"
                    value="{{ old('name') }}"
                    required>
            </div>

            <div class="input-group">
                <input
                    type="text"
                    name="username"
                    placeholder="Username"
                    value="{{ old('username') }}"
                    required>
            </div>

            <div class="input-group">
                <select name="role" id="role" required onchange="ubahPlaceholderIdentitas()">
                    <option value="">Pilih Role</option>
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                </select>
            </div>

            <div class="input-group">
                <input
                    type="text"
                    id="nim_nidn"
                    name="nim_nidn"
                    placeholder="NIM / NIP / NIDN"
                    value="{{ old('nim_nidn') }}"
                    required>
            </div>

            <div class="input-group">
                <input
                    type="text"
                    name="nomor_hp"
                    placeholder="Nomor Telepon"
                    value="{{ old('nomor_hp') }}"
                    required>
            </div>

            <div class="input-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Kata Sandi"
                    required>
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

    <script>
        function ubahPlaceholderIdentitas() {
            const role = document.getElementById('role').value;
            const inputIdentitas = document.getElementById('nim_nidn');

            if (role === 'mahasiswa') {
                inputIdentitas.placeholder = 'NIM';
            } else if (role === 'staff') {
                inputIdentitas.placeholder = 'NIP';
            } else if (role === 'dosen') {
                inputIdentitas.placeholder = 'NIDN';
            } else {
                inputIdentitas.placeholder = 'NIM / NIP / NIDN';
            }
        }

        ubahPlaceholderIdentitas();
    </script>

</body>

</html>
