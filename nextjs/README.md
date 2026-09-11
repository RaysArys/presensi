# Presensi Kita Next.js

Migrasi Next.js dari aplikasi Laravel Presensi Kita. Aplikasi memakai database MySQL existing melalui Prisma.

## Local setup

1. Copy `.env.example` menjadi `.env` dan isi `DATABASE_URL` serta `AUTH_SECRET`.
2. Jalankan `npm install`.
3. Jalankan `npx prisma generate`.
4. Jalankan `npm run dev`.

Database existing Laravel dapat langsung dipakai karena nama tabel dan kolom dipetakan di `prisma/schema.prisma`. Jangan menjalankan `prisma db push` pada database existing.

## Vercel

Import folder `nextjs` sebagai root directory. Set `DATABASE_URL` ke MySQL cloud dan `AUTH_SECRET` ke secret acak panjang di Project Settings.