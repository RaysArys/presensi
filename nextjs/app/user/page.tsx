import { redirect } from 'next/navigation'
import { getSession } from '@/lib/auth'
import { db } from '@/lib/db'
import AttendancePanel from '@/components/AttendancePanel'

export default async function UserPage() {
  const session = await getSession()
  if (!session || !['staff', 'supervisor'].includes(session.role) || !session.pegawaiId) redirect('/login')
  const today = new Date(new Date().toDateString())
  const [pegawai, lokasi, hariIni, riwayat] = await Promise.all([
    db.pegawai.findUnique({ where: { id: session.pegawaiId }, include: { jabatan: true } }),
    db.lokasiPresensi.findFirst({ where: { status: 'aktif' } }),
    db.presensi.findUnique({ where: { pegawaiId_tanggal: { pegawaiId: session.pegawaiId, tanggal: today } } }),
    db.presensi.findMany({ where: { pegawaiId: session.pegawaiId }, orderBy: { tanggal: 'desc' }, take: 7 }),
  ])
  if (!pegawai) redirect('/login')
  return <main className="dashboard"><header className="topbar"><div><p className="eyebrow">PRESENSI KITA / {session.role.toUpperCase()}</p><h1>Halo, {pegawai.nama.split(' ')[0]}.</h1><p className="muted">Catat kehadiranmu dengan GPS dan foto langsung.</p></div><form action="/api/auth/logout" method="post"><button className="outline">Keluar</button></form></header><section className="user-hero"><div><p className="eyebrow">STATUS HARI INI</p><h2>{hariIni?.jamPulang ? 'Presensi sudah lengkap' : hariIni ? 'Sudah presensi masuk' : 'Belum melakukan presensi'}</h2><p className="muted">{lokasi ? `${lokasi.namaLokasi} / radius ${lokasi.radiusMeter} meter` : 'Lokasi belum diatur Admin'}</p></div><strong className="clock">{new Date().toLocaleTimeString('id-ID')}</strong></section><section className="user-grid"><AttendancePanel lokasi={lokasi ? { latitude: Number(lokasi.latitude), longitude: Number(lokasi.longitude), radiusMeter: lokasi.radiusMeter } : null} alreadyIn={Boolean(hariIni)} alreadyOut={Boolean(hariIni?.jamPulang)} /><article className="panel"><p className="eyebrow">RIWAYAT</p><h2>Presensi terbaru</h2><div className="rows">{riwayat.map((item) => <div className="row" key={item.id}><div><strong>{item.tanggal.toLocaleDateString('id-ID')}</strong><small>{item.jamMasuk?.slice(0, 5) || '--:--'} sampai {item.jamPulang?.slice(0, 5) || '--:--'}</small></div><span className={`pill ${item.status}`}>{item.status}</span></div>)}</div></article></section></main>
}