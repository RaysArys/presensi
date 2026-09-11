import './globals.css'
import type { Metadata } from 'next'

export const metadata: Metadata = { title: 'Presensi Kita', description: 'Presensi karyawan berbasis lokasi dan foto' }

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) { return <html lang="id"><body>{children}</body></html> }