<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Presensi Kita') — Presensi Kita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand"><span class="brand-mark">P</span><div><strong></strong><small></small></div></div>
        <nav class="nav-menu">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard')?'active':'' }}"><span>⌂</span> Dashboard</a>
                <a href="{{ route('admin.pegawai.index') }}" class="nav-link {{ request()->routeIs('admin.pegawai.*')?'active':'' }}"><span>♙</span> Kelola Karyawan</a>
                <a href="{{ route('admin.lokasi.index') }}" class="nav-link {{ request()->routeIs('admin.lokasi.*')?'active':'' }}"><span>⌖</span> Lokasi Presensi</a>
                <a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap.*')?'active':'' }}"><span>▦</span> Presensi & Rekap</a>
                <a href="{{ route('admin.ketidakhadiran.index') }}" class="nav-link {{ request()->routeIs('admin.ketidakhadiran.*')?'active':'' }}"><span>◷</span> Cuti & Izin</a>
            @else
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard')?'active':'' }}"><span>⌂</span> Dashboard & Presensi</a>
                <a href="{{ route('user.ketidakhadiran.index') }}" class="nav-link {{ request()->routeIs('user.ketidakhadiran.*')?'active':'' }}"><span>◷</span> Pengajuan Saya</a>
            @endif
        </nav>
        <div class="sidebar-user"><div class="avatar">{{ strtoupper(substr(auth()->user()->pegawai?->nama ?? 'A',0,1)) }}</div><div class="user-copy"><strong>{{ auth()->user()->pegawai?->nama ?? 'Administrator' }}</strong><small>{{ ucfirst(auth()->user()->role) }}</small></div><form method="POST" action="{{ route('logout') }}">@csrf<button class="logout" title="Keluar">↪</button></form></div>
    </aside>
    <main class="main-content">
        <header class="topbar"><button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button><div><h1>@yield('page-title')</h1><p>@yield('page-subtitle')</p></div><div class="date-chip">{{ now()->translatedFormat('l, d F Y') }}</div></header>
        <section class="content">
            @if(session('success'))<div class="alert success">✓ {{ session('success') }}</div>@endif
            @if($errors->any())<div class="alert danger"><strong>Periksa kembali:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </section>
    </main>
</div>
@stack('scripts')
</body>
</html>
