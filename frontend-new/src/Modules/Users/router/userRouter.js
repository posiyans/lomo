export const adminUser =
  {
    path: 'user',
    meta: {
      icon: 'people',
      title: 'Пользователи',
      roles: ['user-show', 'user-edit']
    },
    redirect: { name: 'adminUserList' },
    children: [
      {
        path: 'list',
        name: 'adminUserList',
        component: () => import('src/Modules/Users/pages/AdminUserList/index.vue'),
        meta: {
          icon: 'people',
          title: 'Список',
          roles: ['user-show', 'user-edit']
        }
      },
      {
        path: 'show/:uid',
        component: () => import('src/Modules/Users/pages/AdminUserInfo/index.vue'),
        hidden: true,
        meta: {
          icon: 'people',
          title: 'Профиль пользвателя',
          roles: ['user-show', 'user-edit']
        }
      }
    ]
  }
