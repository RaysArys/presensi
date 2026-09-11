import { redirect } from 'next/navigation'
import { getSession } from '@/lib/auth'
import { db } from '@/lib/db'
import AttendancePanel from '@/components/AttendancePanel'
import AppShell from '@/components/AppShell'
import { formatTime } from '@/lib/time'

export default async function UserPage() {
  const session = await getSession()
  if (!session || !['staff', 'supervisor'].includes(session.role) || !session.pegawaiId) redirect('/login')
  const today = new Date(new Date().toDateString())
  let pegawai; let lokasi; let hariIni; let riwayat
  try {
    [pegawai, lokasi, hariIni, riwayat] = await Promise.all([
      db.pegawai.findUnique({ where: { id: session.pegawaiId }, include: { jabatan: true } }),
      db.lokasiPresensi.findFirst({ where: { status: 'aktif' } }),
      db.presensi.findFirst({ where: { pegawaiId: session.pegawaiId, tanggal: today } }),
      db.presensi.findMany({ where: { pegawaiId: session.pegawaiId }, orderBy: { tanggal: 'desc' }, take: 7 }),
    ])
  } catch (error) {
    console.error('User dashboard database error:', error)
    return <main className="dashboard"><div className="panel"><p className="eyebrow">DATABASE ERROR</p><h1>Dashboard user belum bisa dibuka</h1><p className="muted">Data akun belum cocok dengan struktur database Railway. Pastikan tabel `pegawai`, `presensi`, dan `lokasi_presensi` sudah di-import dari database Laravel, lalu redeploy.</p></div></main>
  }
  if (!pegawai) redirect('/login')
    return <AppShell><main className="dashboard"><header className="topbar"><div><p className="eyebrow">PRESENSI KITA / {session.role.toUpperCase()}</p><h1>Halo, {pegawai.nama.split(' ')[0]}.</h1><p className="muted">Catat kehadiranmu dengan GPS dan foto langsung.</p></div></header><section className="user-hero"><div><p className="eyebrow">STATUS HARI INI</p><h2>{hariIni?.jamPulang ? 'Presensi sudah lengkap' : hariIni ? 'Sudah presensi masuk' : 'Belum melakukan presensi'}</h2><p className="muted">{lokasi ? `${lokasi.namaLokasi} / radius ${lokasi.radiusMeter} meter` : 'Lokasi belum diatur Admin'}</p></div><strong className="clock">{new Date().toLocaleTimeString('id-ID')}</strong></section><section className="user-grid"><AttendancePanel lokasi={lokasi ? { latitude: Number(lokasi.latitude), longitude: Number(lokasi.longitude), radiusMeter: lokasi.radiusMeter } : null} alreadyIn={Boolean(hariIni)} alreadyOut={Boolean(hariIni?.jamPulang)} /><article className="panel"><p className="eyebrow">RIWAYAT</p><h2>Presensi terbaru</h2><div className="rows">{riwayat.map((item) => <div className="row" key={item.id}><div><strong>{item.tanggal.toLocaleDateString('id-ID')}</strong><small>{formatTime(item.jamMasuk) || '--:--'} sampai {formatTime(item.jamPulang) || '--:--'}</small></div><span className={`pill ${item.status}`}>{item.status}</span></div>)}</div></article></section></main></AppShell>
}