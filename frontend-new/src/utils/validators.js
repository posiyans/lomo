const required = function (val) {
  return ((val && val.length > 0) || 'Поле должно быть заполнено')
}

const short = function (val, length = 3) {
  return ((val && val.length > length) || 'Минимальная длина ' + length + ' символов')
}

const passwordConfirm = function (confirm, password) {
  return ((confirm && confirm === password) || 'Пароли не совпадают')
}

const passwordCapital = function (val) {
  return ((/^(?=.*[A-Z, А-Я])/.test(val)) || 'Поле долно содержать строчные буквы')
}

const passwordNumber = function (val) {
  return ((/^(?=.*[0-9])/.test(val)) || 'Поле долно содержать цифры')
}

const passwordSymbol = function (val) {
  return ((/^(?=.*[!@#\$%\^&\*_\-=+])/.test(val)) || 'Поле долно содержать спец сиволы')
}

const isEmail = function (val) {
  const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/
  return (emailPattern.test(val) || 'Введите корректный email')
}

const isExternal = function (path) {
  return /^(https?:|mailto:|tel:)/.test(path)
}

export {
  passwordSymbol,
  passwordCapital,
  passwordNumber,
  passwordConfirm,
  isExternal,
  required,
  isEmail,
  short
}