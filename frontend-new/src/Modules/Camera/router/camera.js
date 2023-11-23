const camera =
  {
    path: '/camera',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'show',
        component: () => import('src/Modules/Camera/page/Show/index.vue'),
        hidden: true,
        meta: {
          title: 'Просмотр камер'
        }
      }
    ]
  }
export default camera
