const yandex =
  {
    path: '/yandex',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'schedule',
        component: () => import('src/Modules/Yandex/page/YandexSchedule/index.vue'),
        hidden: true,
        meta: {
          title: 'Яндекс расписание'
        }
      }
    ]
  }
export default yandex
