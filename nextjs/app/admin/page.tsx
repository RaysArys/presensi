import { redirect } from 'next/navigation'
import { getSession } from '@/lib/auth'
import { db } from '@/lib/db'
import AppShell from '@/components/AppShell'

export default async function AdminPage() {
  const session = await getSession()
  if (!session || session.role !== 'admin') redirect('/login')
  const today = new Date(new Date().toDateString())
  const [employees, attendance, pending] = await Promise.all([
    db.pegawai.count({ where: { statusPegawai: 'aktif' } }),
    db.presensi.findMany({ where: { tanggal: today }, include: { pegawai: true }, orderBy: { createdAt: 'desc' } }),
    db.ketidakhadiran.findMany({ where: { statusPengajuan: 'menunggu' }, include: { pegawai: true }, take: 5, orderBy: { createdAt: 'desc' } }),
  ])
  const late = attendance.filter((item) => item.status === 'terlambat').length
    return <AppShell><main className="dashboard"><header className="topbar"><div><p className="eyebrow">PRESENSI KITA / ADMIN</p><h1>Dashboard</h1><p className="muted">Ringkasan aktivitas presensi hari ini.</p></div></header><section className="stats"><article><span>Karyawan aktif</span><strong>{employees}</strong></article><article><span>Hadir</span><strong>{attendance.length - late}</strong></article><article><span>Terlambat</span><strong>{late}</strong></article><article><span>Menunggu</span><strong>{pending.length}</strong></article></section><section className="dashboard-grid"><article className="panel"><p className="eyebrow">HARI INI</p><h2>Presensi terbaru</h2><div className="rows">{attendance.length ? attendance.map((item) => <div className="row" key={item.id}><div><strong>{item.pegawai.nama}</strong><small>{item.jamMasuk?.slice(0, 5) || '--:--'}</small></div><span className={`pill ${item.status}`}>{item.status}</span></div>) : <p className="muted">Belum ada presensi hari ini.</p>}</div></article><article className="panel"><p className="eyebrow">PERLU TINDAKAN</p><h2>Pengajuan terbaru</h2><div className="rows">{pending.length ? pending.map((item) => <div className="row" key={item.id}><div><strong>{item.pegawai.nama}</strong><small>{item.jenis} / {item.kategoriAlasan}</small></div><span className="pill pending">Menunggu</span></div>) : <p className="muted">Tidak ada pengajuan baru.</p>}</div></article></section></main></AppShell>
}