<form method="POST" action="/register">
    @csrf
    <input type="text" name="name" placeholder="Nama" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="role" required>
        <option value="staff">Staff</option>
        <option value="mahasiswa">Mahasiswa</option>
        <option value="dosen">Dosen</option>
    </select>

    <button type="submit">Register</button>
</form>
