'use client'

import { useRef, useState } from 'react'

type Props = { lokasi: { latitude: number; longitude: number; radiusMeter: number } | null; alreadyIn: boolean; alreadyOut: boolean }

export default function AttendancePanel({ lokasi, alreadyIn, alreadyOut }: Props) {
  const video = useRef<HTMLVideoElement>(null)
  const [stream, setStream] = useState<MediaStream | null>(null)
  const [photo, setPhoto] = useState('')
  const [message, setMessage] = useState('')
  const [busy, setBusy] = useState(false)
  async function startCamera() { try { const nextStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false }); setStream(nextStream); if (video.current) video.current.srcObject = nextStream } catch { setMessage('Kamera tidak dapat diakses. Izinkan kamera pada browser.') } }
  function capture() { if (!video.current) return; const canvas = document.createElement('canvas'); canvas.width = 640; canvas.height = 480; canvas.getContext('2d')?.drawImage(video.current, 0, 0, 640, 480); setPhoto(canvas.toDataURL('image/jpeg', .78)); setMessage('Foto siap digunakan.') }
  async function submit(aksi: 'masuk' | 'pulang') { setBusy(true); setMessage('Meminta lokasi perangkat...'); if (!lokasi || !navigator.geolocation) { setMessage('Lokasi presensi belum tersedia atau browser tidak mendukung GPS.'); setBusy(false); return } navigator.geolocation.getCurrentPosition(async (position) => { const response = await fetch('/api/presensi', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ aksi, latitude: position.coords.latitude, longitude: position.coords.longitude, akurasi: position.coords.accuracy, foto: photo }) }); const data = await response.json(); setMessage(data.message || data.error); if (response.ok) window.location.reload(); else setBusy(false) }, () => { setMessage('Akses lokasi ditolak. Izinkan GPS lalu coba lagi.'); setBusy(false) }, { enableHighAccuracy: true, timeout: 15000 }) }
  return <article className="panel attendance"><p className="eyebrow">PRESENSI SEKARANG</p><h2>{lokasi ? 'Siap mencatat kehadiran?' : 'Lokasi belum siap'}</h2><p className="muted">Aktifkan kamera dan GPS sebelum mengirim presensi.</p><div className="camera-frame">{stream ? <video ref={video} autoPlay playsInline /> : <span>Kamera belum aktif</span>}</div><div className="camera-actions"><button type="button" className="outline" onClick={startCamera}>Aktifkan kamera</button>{stream && <button type="button" className="outline" onClick={capture}>Ambil foto</button>}</div>{photo && <img className="photo-preview" src={photo} alt="Pratinjau foto presensi" />}{message && <div className="alert error">{message}</div>}<div className="actions">{!alreadyIn ? <button disabled={busy || !photo} onClick={() => submit('masuk')}>Presensi masuk</button> : !alreadyOut ? <button disabled={busy || !photo} onClick={() => submit('pulang')}>Presensi pulang</button> : <button disabled>Presensi selesai</button>}</div></article>
}
