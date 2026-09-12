const jakartaFormat = new Intl.DateTimeFormat('en-GB', {
  timeZone: 'Asia/Jakarta',
  hour: '2-digit',
  minute: '2-digit',
  second: '2-digit',
  hour12: false,
})

export function timeFromInput(value: string) {
  const [hours = 0, minutes = 0, seconds = 0] = value.split(':').map(Number)
  return new Date(Date.UTC(1970, 0, 1, hours, minutes, seconds))
}

export function formatTime(value: Date | null | undefined) {
  if (!value) return ''

  const parts = jakartaFormat.formatToParts(value)
  const hour = parts.find((part) => part.type === 'hour')?.value ?? '00'
  const minute = parts.find((part) => part.type === 'minute')?.value ?? '00'

  return `${hour}:${minute}`
}

export function timeValue(value: Date | string) {
  if (value instanceof Date) {
    const parts = jakartaFormat.formatToParts(value)
    const hours = Number(parts.find((part) => part.type === 'hour')?.value ?? '0')
    const minutes = Number(parts.find((part) => part.type === 'minute')?.value ?? '0')
    const seconds = Number(parts.find((part) => part.type === 'second')?.value ?? '0')

    return hours * 3600 + minutes * 60 + seconds
  }

  const [hours = 0, minutes = 0, seconds = 0] = value.split(':').map(Number)
  return hours * 3600 + minutes * 60 + seconds
}
