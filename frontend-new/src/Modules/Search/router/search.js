const search =
  {
    path: '/search',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: '',
        component: () => import('src/Modules/Search/pages/SearchPage/index.vue'),
        name: 'SearchPage',
        hidden: true,
        meta: {
          title: 'Поиск по сайту'
        }
      }
    ]
  }
export default search
