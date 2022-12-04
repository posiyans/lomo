export const authUser = {
  path: '/auth',
  component: () => import('layouts/UserLayout/index.vue'),
  children: [
    {
      path: 'password-reset',
      component: () => import('src/Modules/User/page/Auth/PasswordReset/index.vue'),
      hidden: true,
      meta: {
        title: 'Вспомнить пароль'
      }
    }
  ]
}

export const asyncUser = {
  path: '/new-admin',
  component: () => import('layouts/AdminLayout/index.vue'),
  meta: {
    title: 'sdfsdfsadfsfd'
  },
  children: [
    {
      path: 'test',
      component: () => import('src/Modules/User/page/Auth/PasswordReset/index.vue'),
      hidden: true,
      meta: {
        title: 'Вспомнить пароль'
      }
    },
    {
      path: 'testsdfsfdsdf',
      component: () => import('src/Modules/User/page/Auth/PasswordReset/index.vue'),
      hidden: true,
      meta: {
        title: 'Вспомнить пароль',
        roles: ['sdsadasda'],
        icon: 'star'
      }
    }
  ]
}
