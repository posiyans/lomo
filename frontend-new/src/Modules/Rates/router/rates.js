const rates =
  {
    path: '/modules',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'rates',
        component: () => import('src/Modules/Rates/pages/List/index.vue'),
        name: 'modulesRates',
        hidden: true
      }
    ]
  }
export default rates
