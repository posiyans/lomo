export const stead =
  {}
export const adminStead =
  {
    path: 'stead',
    redirect: { name: 'adminSteadList' },
    meta: {
      icon: 'view_module',
      title: 'Участки',
      roles: ['stead-show']
    },
    children: [
      {
        path: 'list',
        name: 'adminSteadList',
        component: () => import('src/Modules/Stead/pages/SteadList/index.vue'),
        hidden: true,
        meta: {
          icon: 'view_module',
          title: 'Участки',
          roles: ['stead-show']
        }
      },
      {
        path: 'info/:id',
        component: () => import('src/Modules/Stead/pages/SteadInfo/index.vue'),
        hidden: true,
        meta: {
          icon: 'view_module',
          title: 'Участок',
          roles: ['stead-show']
        }
      }
    ]
  }
