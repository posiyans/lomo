export function getMenu() {
  const menu = [
    {
      label: 'Личный кабинет',
      children: [
        {
          label: 'Собственник',
          path: '/personal-area/owner'
        },
        {
          label: 'Участок',
          path: '/personal-area/stead'
        },
        {
          label: 'Счета и платежи',
          path: '/personal-area/invoice'
        },
        {
          label: 'Показания',
          path: '/personal-area/instrument-reading'
        },
        {
          label: 'Обращения',
          path: '/personal-area/appeal'
        }
      ]
    }
  ]
  return menu
}
