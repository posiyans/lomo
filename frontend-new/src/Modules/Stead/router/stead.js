export const stead =
  {}
export const adminStead =
  {
    path: 'stead/list',
    name: 'adminSteadList',
    component: () => import('src/Modules/Stead/pages/SteadList/index.vue'),
    meta: {
      icon: 'view_module',
      title: 'Участки',
      roles: ['stead-show']
    }
  }
