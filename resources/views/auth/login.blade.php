<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login — Presensi Kita</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('css/app.css') }}"></head>
<body class="login-page">
<div class="login-card">
    <section class="login-visual"><div class="visual-badge"></div><div><h1>Kerja lebih tertib,<br>tanpa terasa rumit.</h1><p>Presensi berbasis lokasi dan foto untuk pencatatan yang praktis, akurat, dan transparan.</p></div><div class="visual-pills"></div></section>
    <section class="login-form-wrap"><div class="mobile-brand"><span class="brand-mark">P</span> Presensi Kita</div><div class="login-heading"><span>Selamat datang</span><h2>Masuk ke akun Anda</h2><p>Gunakan username dan password yang diberikan Admin.</p></div>
        @if($errors->any())<div class="alert danger">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('login.process') }}" class="form-stack">@csrf
            <label>Username<input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus></label>
            <label>Password<div class="password-wrap"><input id="password" type="password" name="password" placeholder="Masukkan password" required><button type="button" onclick="let p=document.getElementById('password');p.type=p.type==='password'?'text':'password'">◉</button></div></label>
            <label>Masuk sebagai<select name="role" required><option value="staff" @selected(old('role','staff')==='staff')>Staff</option><option value="admin" @selected(old('role')==='admin')>Admin</option><option value="supervisor" @selected(old('role')==='supervisor')>Supervisor</option></select></label>
            <label class="check-row"><input type="checkbox" name="remember"> Ingat saya</label>
            <button class="btn btn-primary btn-block">Masuk</button>
        </form><p class="login-note">Akun dikelola oleh Administrator.</p>
    </section>
</div>
</body></html>
