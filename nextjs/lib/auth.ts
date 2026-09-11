import { SignJWT, jwtVerify } from 'jose'
import { cookies } from 'next/headers'

const secret = new TextEncoder().encode(process.env.AUTH_SECRET || 'development-secret-change-me')
const cookieName = 'presensi_session'

export type Session = { userId: number; role: string; pegawaiId: number | null }

export async function createSession(session: Session) {
  const token = await new SignJWT(session).setProtectedHeader({ alg: 'HS256' }).setIssuedAt().setExpirationTime('7d').sign(secret)
  const store = await cookies()
  store.set(cookieName, token, { httpOnly: true, sameSite: 'lax', secure: process.env.NODE_ENV === 'production', maxAge: 60 * 60 * 24 * 7, path: '/' })
}

export async function getSession(): Promise<Session | null> {
  const token = (await cookies()).get(cookieName)?.value
  if (!token) return null
  try { return (await jwtVerify(token, secret)).payload as unknown as Session } catch { return null }
}

export async function clearSession() { (await cookies()).delete(cookieName) }