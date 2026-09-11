import { PrismaClient } from '@prisma/client'
import bcrypt from 'bcryptjs'

const db = new PrismaClient()

async function main() {
  const password = await bcrypt.hash('admin123', 12)
  await db.user.upsert({ where: { username: 'admin' }, update: {}, create: { username: 'admin', password, role: 'admin', statusAkun: 'aktif' } })
  console.log('Admin demo siap: admin / admin123')
}

main().finally(() => db.$disconnect())
