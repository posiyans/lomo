const required = function (val) {
  return ((val && val.length > 0) || 'Поле должно быть заполнено')
}

const short = function (val) {
  return ((val && val.length > 3) || 'Значение слишком короткое')
}

const isEmail = function (val) {
  const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/
  return (emailPattern.test(val) || 'Введите корректный email')
}

export {
  required,
  isEmail,
  short
}
