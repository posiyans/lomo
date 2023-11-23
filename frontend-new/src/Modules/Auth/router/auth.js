export const authUser = {
  path: '/auth',
  component: () => import('layouts/UserLayout/index.vue'),
  children: [
    {
      path: 'reset-password',
      name: 'UserResetPassword',
      component: () => import('src/Modules/Auth/page/Auth/PasswordReset/index.vue'),
      hidden: true,
      meta: {
        title: 'Вспомнить пароль',
        guest: true
      }
    },
    {
      path: 'change-password',
      name: 'UserChangePassword',
      component: () => import('src/Modules/Auth/page/Auth/ChangePassword/index.vue'),
      hidden: true,
      meta: {
        title: 'Сменить пароль',
        guest: true
      }
    },
    {
      path: 'login',
      name: 'UserLogin',
      component: () => import('src/Modules/Auth/page/Auth/Login/index.vue'),
      hidden: true,
      meta: {
        title: 'Войти',
        guest: true
      }
    },
    {
      path: 'register',
      name: 'UserRegister',
      component: () => import('src/Modules/Auth/page/Auth/Register/index.vue'),
      hidden: true,
      meta: {
        title: 'Регистрация',
        guest: true
      }
    }
  ]
}
