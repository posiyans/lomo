export const personalAreaRouter =
  {
    path: '/personal-area',
    component: () => import('layouts/UserLayout/index.vue'),
    meta: {
      roles: ['owner']
    },
    children: [
      {
        path: '',
        name: 'UserDashboard',
        component: () => import('src/Modules/PersonalArea/pages/Dashboard/index.vue'),
        meta: {
          title: 'Личный кабинет',
          roles: ['owner'],
          redirect: 'UserLogin'
        }
      },
      {
        path: 'profile',
        name: 'UserProfile',
        component: () => import('src/Modules/PersonalArea/pages/Profile/index.vue'),
        meta: {
          title: 'Профиль',
          roles: ['owner', 'user'],
          redirect: 'UserLogin'
        }
      },
      {
        path: 'stead',
        component: () => import('src/Modules/PersonalArea/pages/MySteads/index.vue'),
        meta: {
          title: 'Участок',
          roles: ['owner']
        }
      }

    ]
  }
