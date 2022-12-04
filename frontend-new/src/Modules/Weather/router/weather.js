const weather =
  {
    path: '/weather',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'show',
        component: () => import('src/Modules/Weather/page/WeatherShow/index.vue'),
        hidden: true,
        meta: {
          title: ''
        }
      }
    ]
  }
export default weather
