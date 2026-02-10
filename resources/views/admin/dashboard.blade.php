<h1>Admin Dashboard QRAttend</h1>

<p>Selamat datang, Admin!</p>

<form method="POST" action="{{ route('admin.logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
