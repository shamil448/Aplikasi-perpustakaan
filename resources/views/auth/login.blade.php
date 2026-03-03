<form method="POST" action="/login">
    @csrf
    <input type="text" name="name" placeholder="Nama" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
