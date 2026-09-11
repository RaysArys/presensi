import { db } from '@/lib/db'

export async function dailyStatuses(date: Date) {
  const employees = await db.pegawai.findMany({ where: { statusPegawai: 'aktif' }, include: { jabatan: true }, orderBy: { nama: 'asc' } })
  const [attendance, leave, holiday] = await Promise.all([
    db.presensi.findMany({ where: { tanggal: date } }),
    db.ketidakhadiran.findMany({ where: { statusPengajuan: 'disetujui', tanggalMulai: { lte: date }, tanggalSelesai: { gte: date } } }),
    db.hariLibur.findUnique({ where: { tanggal: date } }),
  ])
  const attendanceByEmployee = new Map(attendance.map((item) => [item.pegawaiId, item]))
  const leaveByEmployee = new Map(leave.map((item) => [item.pegawaiId, item]))
  const now = new Date()
  const isWeekend = date.getDay() === 0 || date.getDay() === 6
  return employees.map((pegawai) => { const presensi = attendanceByEmployee.get(pegawai.id); const izin = leaveByEmployee.get(pegawai.id); let status = presensi?.status || izin?.jenis; if (!status) status = holiday || isWeekend ? 'libur' : date > new Date(new Date().toDateString()) || (date.toDateString() === new Date().toDateString() && now.getHours() < 17) ? 'belum presensi' : 'alpa'; return { pegawai, presensi, status } })
}