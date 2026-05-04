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

export function maskCep(value) {
  if (!value) return ''

  value = value.toString().replace(/\D/g, '')
  value = value.slice(0, 8)

  if (value.length > 5) {
    value = value.replace(/^(\d{5})(\d)/, '$1-$2')
  }

  return value
}
