'use client'

import { useState } from 'react'

type Props = { lokasi: { latitude: number; longitude: number; radiusMeter: number } | null; alreadyIn: boolean; alreadyOut: boolean }
export default function AttendancePanel({ lokasi, alreadyIn, alreadyOut }: Props) {
  const [message, setMessage] = useState(''); const [busy, setBusy] = useState(false)
  async function submit(aksi: 'masuk' | 'pulang') { setBusy(true); setMessage('Meminta lokasi perangkat...'); if (!lokasi || !navigator.geolocation) { setMessage('Lokasi presensi belum tersedia atau browser tidak mendukung GPS.'); setBusy(false); return } navigator.geolocation.getCurrentPosition(async (position) => { const response = await fetch('/api/presensi', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ aksi, latitude: position.coords.latitude, longitude: position.coords.longitude, akurasi: position.coords.accuracy }) }); const data = await response.json(); setMessage(data.message || data.error); if (response.ok) window.location.reload(); else setBusy(false) }, () => { setMessage('Akses lokasi ditolak. Izinkan GPS lalu coba lagi.'); setBusy(false) }, { enableHighAccuracy: true, timeout: 15000 }) }
  return <article className="panel attendance"><p className="eyebrow">PRESENSI SEKARANG</p><h2>{lokasi ? 'Siap mencatat kehadiran?' : 'Lokasi belum siap'}</h2><p className="muted">GPS aktif diperlukan untuk melakukan presensi.</p>{message && <div className="alert error">{message}</div>}<div className="actions">{!alreadyIn ? <button disabled={busy} onClick={() => submit('masuk')}>Presensi masuk</button> : !alreadyOut ? <button disabled={busy} onClick={() => submit('pulang')}>Presensi pulang</button> : <button disabled>Presensi selesai</button>}</div></article>
}