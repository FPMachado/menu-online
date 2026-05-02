export function maskTelefone(value) {
  if (!value) return ''

  value = value.toString().replace(/\D/g, '')

  if (value.length <= 10) {
    value = value.replace(/^(\d{2})(\d)/g, '($1) $2')
    value = value.replace(/(\d{4})(\d)/, '$1-$2')
  } else {
    value = value.replace(/^(\d{2})(\d)/g, '($1) $2')
    value = value.replace(/(\d{5})(\d)/, '$1-$2')
  }

  return value
}
