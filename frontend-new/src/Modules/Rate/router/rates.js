const rates =
  {
    path: '/modules',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'rates',
        component: () => import('src/Modules/Rate/pages/List/index.vue'),
        name: 'modulesRates',
        hidden: true,
        meta: {
          title: 'Тарифы'
        }
      }
    ]
  }
export default rates
