export const asyncAdmin =
  {
    path: '/m',
    component: () => import('layouts/AdminLayout/index.vue'),
    meta: {
      title: '1'
    },
    children: [
      {
        path: 't',
        component: () => import('src/Modules/User/page/Auth/PasswordReset/index.vue'),
        hidden: true,
        meta: {
          title: '2'
        }
      }
    ]
  }
