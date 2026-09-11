import { NextResponse } from 'next/server'
import bcrypt from 'bcryptjs'
import { z } from 'zod'
import { db } from '@/lib/db'
import { createSession } from '@/lib/auth'

const schema = z.object({ username: z.string().min(1), password: z.string().min(1), role: z.enum(['admin', 'staff', 'supervisor']) })

export async function POST(request: Request) {
  const parsed = schema.safeParse(await request.json())
  if (!parsed.success) return NextResponse.json({ error: 'Data login tidak valid.' }, { status: 422 })
  const user = await db.user.findUnique({ where: { username: parsed.data.username } })
  if (!user || user.role !== parsed.data.role || user.statusAkun !== 'aktif' || !(await bcrypt.compare(parsed.data.password, user.password))) return NextResponse.json({ error: 'Username, password, atau role tidak sesuai.' }, { status: 401 })
  await createSession({ userId: user.id, role: user.role, pegawaiId: user.pegawaiId })
  return NextResponse.json({ role: user.role })
}