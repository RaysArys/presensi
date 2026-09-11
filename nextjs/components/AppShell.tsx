import { getSession } from '@/lib/auth'

export default async function AppShell({ children }: { children: React.ReactNode }) {
  const session = await getSession()
  const isAdmin = session?.role === 'admin'
  return <div className="app-shell"><aside className="sidebar"><div className="brand-block"><span className="brand-mark">P</span><strong>Presensi Kita</strong></div><nav><a href={isAdmin ? '/admin' : '/user'} className="nav-item">⌂ <span>Dashboard</span></a>{isAdmin ? <><a href="/admin/pegawai" className="nav-item">♙ <span>Kelola Karyawan</span></a><a href="/admin/lokasi" className="nav-item">⌖ <span>Lokasi Presensi</span></a><a href="/admin/rekap" className="nav-item">▦ <span>Presensi & Rekap</span></a><a href="/admin/pengajuan" className="nav-item">◷ <span>Cuti & Izin</span></a></> : <a href="/user/pengajuan" className="nav-item">◷ <span>Pengajuan Saya</span></a>}</nav><div className="sidebar-bottom"><small>{session?.role ? session.role.charAt(0).toUpperCase() + session.role.slice(1) : 'Pengguna'}</small><form action="/api/auth/logout" method="post"><button className="logout-button">Keluar</button></form></div></aside><div className="page-area">{children}</div></div>
}
