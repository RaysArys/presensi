export function timeFromInput(value: string) {
  const [hours, minutes] = value.split(':').map(Number)
  const result = new Date(1970, 0, 1, hours || 0, minutes || 0, 0)
  return result
}

export function formatTime(value: Date | null | undefined) {
  if (!value) return ''

  const hours = value.getHours().toString().padStart(2, '0')
  const minutes = value.getMinutes().toString().padStart(2, '0')

  return `${hours}:${minutes}`
}

export function timeValue(value: Date | string) {
  if (value instanceof Date) return value.getHours() * 3600 + value.getMinutes() * 60 + value.getSeconds()
  const [hours = 0, minutes = 0, seconds = 0] = value.split(':').map(Number)
  return hours * 3600 + minutes * 60 + seconds
}
