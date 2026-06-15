<form method="POST" action="{{ route('simalex.password.email') }}">
    @csrf

    <h1>Lupa Password SIMALEX</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <input type="email" name="email" placeholder="Email" required>

    <button type="submit">Kirim Link Reset Password</button>

    <a href="/admin/login">Kembali ke Login</a>
</form>