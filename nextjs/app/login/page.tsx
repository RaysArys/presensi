'use client'

import { FormEvent, useState } from 'react'
import { useRouter } from 'next/navigation'

export default function LoginPage() {
  const router = useRouter(); const [error, setError] = useState(''); const [loading, setLoading] = useState(false)
  async function submit(event: FormEvent<HTMLFormElement>) { event.preventDefault(); setLoading(true); setError(''); const body = Object.fromEntries(new FormData(event.currentTarget)); const response = await fetch('/api/auth/login', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) }); const data = await response.json(); if (!response.ok) setError(data.error); else router.push(data.role === 'admin' ? '/admin' : '/user'); setLoading(false) }
  return <main className="login-shell"><section className="login-art"><p className="brand">PRESENSI KITA</p><div><span className="kicker">WORK SMARTER</span><h1>Kerja tertib,<br /><em>tanpa ribet.</em></h1><p>Catat kehadiran dengan lokasi dan foto yang akurat.</p></div></section><section className="login-panel"><p className="eyebrow">SELAMAT DATANG</p><h2>Masuk ke akun Anda</h2><p className="muted">Gunakan kredensial yang diberikan Admin.</p>{error && <div className="alert error">{error}</div>}<form onSubmit={submit} className="stack"><label>Username<input name="username" placeholder="Masukkan username" required /></label><label>Password<input name="password" type="password" placeholder="Masukkan password" required /></label><label>Masuk sebagai<select name="role" defaultValue="staff"><option value="staff">Staff</option><option value="supervisor">Supervisor</option><option value="admin">Admin</option></select></label><button disabled={loading}>{loading ? 'Memproses...' : 'Masuk'}</button></form></section></main>
}