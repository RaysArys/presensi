import { NextResponse } from 'next/server'
import { z } from 'zod'
import { getSession } from '@/lib/auth'
import { db } from '@/lib/db'

const schema = z.object({ statusPengajuan: z.enum(['disetujui', 'ditolak']), catatanAdmin: z.string().max(1000).optional() })
export async function PATCH(request: Request, context: { params: Promise<{ id: string }> }) { const session = await getSession(); if (!session || session.role !== 'admin') return NextResponse.json({ error: 'Akses ditolak.' }, { status: 403 }); const parsed = schema.safeParse(await request.json()); const id = Number((await context.params).id); if (!parsed.success || !Number.isInteger(id)) return NextResponse.json({ error: 'Data tidak valid.' }, { status: 422 }); await db.ketidakhadiran.update({ where: { id }, data: { ...parsed.data, diprosesOleh: session.userId, diprosesPada: new Date() } }); return NextResponse.json({ message: 'Pengajuan berhasil diproses.' }) }