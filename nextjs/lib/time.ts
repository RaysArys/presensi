const jakartaTimeFormatter = new Intl.DateTimeFormat('en-GB', {
  timeZone: 'Asia/Jakarta',
  hour: '2-digit',
  minute: '2-digit',
  second: '2-digit',
  hour12: false,
})

function getJakartaClock(value: Date | string) {
  const date = typeof value === 'string' ? new Date(`1970-01-01T${value}:00Z`) : value
  const parts = jakartaTimeFormatter.formatToParts(date)
  const hours = Number(parts.find((part) => part.type === 'hour')?.value ?? '0')
  const minutes = Number(parts.find((part) => part.type === 'minute')?.value ?? '0')
  const seconds = Number(parts.find((part) => part.type === 'second')?.value ?? '0')

  return { hours, minutes, seconds }
}

export function timeFromInput(value: string) {
  const [hours = 0, minutes = 0, seconds = 0] = value.split(':').map(Number)
  return new Date(Date.UTC(1970, 0, 1, hours, minutes, seconds))
}

export function formatTime(value: Date | string | null | undefined) {
  if (!value) return ''

  const { hours, minutes } = getJakartaClock(value)
  return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`
}

export function timeValue(value: Date | string) {
  const { hours, minutes, seconds } = getJakartaClock(value)
  return hours * 3600 + minutes * 60 + seconds
}
