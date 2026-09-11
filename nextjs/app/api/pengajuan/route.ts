import { NextResponse } from 'next/server'
import { z } from 'zod'
import { getSession } from '@/lib/auth'
import { db } from '@/lib/db'

const schema = z.object({ jenis: z.enum(['izin', 'sakit', 'cuti']), kategoriAlasan: z.string().min(1).max(100), alasan: z.string().min(1).max(1500), tanggalMulai: z.coerce.date(), tanggalSelesai: z.coerce.date() })
export async function POST(request: Request) { const session = await getSession(); if (!session?.pegawaiId) return NextResponse.json({ error: 'Sesi login tidak ditemukan.' }, { status: 401 }); const parsed = schema.safeParse(await request.json()); if (!parsed.success || parsed.data.tanggalSelesai < parsed.data.tanggalMulai) return NextResponse.json({ error: 'Data pengajuan tidak valid.' }, { status: 422 }); const item = await db.ketidakhadiran.create({ data: { pegawaiId: session.pegawaiId, jenis: parsed.data.jenis, kategoriAlasan: parsed.data.kategoriAlasan, alasan: parsed.data.alasan, tanggalMulai: parsed.data.tanggalMulai, tanggalSelesai: parsed.data.tanggalSelesai } }); return NextResponse.json({ id: item.id, message: 'Pengajuan berhasil dikirim.' }, { status: 201 }) }