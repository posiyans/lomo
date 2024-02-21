export const personalAreaRouter =
  {
    path: '/personal-area',
    component: () => import('layouts/UserLayout/index.vue'),
    meta: {
      roles: ['owner']
    },
    children: [
      {
        path: 'owner',
        component: () => import('src/Modules/PersonalArea/pages/Owner/index.vue'),
        meta: {
          title: 'Собственник',
          roles: ['owner']
        }
      },
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
        path: 'stead',
        component: () => import('src/Modules/PersonalArea/pages/MySteads/index.vue'),
        meta: {
          title: 'Участок',
          roles: ['owner']
        }
      },
      {
        path: 'invoice',
        component: () => import('src/Modules/PersonalArea/pages/Invoices/index.vue'),
        meta: {
          title: 'Счета и платежи',
          roles: ['owner']
        }
      },
      {
        path: 'instrument-reading',
        component: () => import('src/Modules/PersonalArea/pages/InstrumentReading/index.vue'),
        meta: {
          title: 'Показания',
          roles: ['owner']
        }
      },
      {
        path: 'appeal',
        component: () => import('src/Modules/PersonalArea/pages/Appeal/index.vue'),
        meta: {
          title: 'Обращения',
          roles: ['owner']
        }
      }

    ]
  }
