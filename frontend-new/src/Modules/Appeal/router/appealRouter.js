export const adminAppeal =
  {
    path: 'appeal',
    redirect: { name: 'adminAppealsList' },
    meta: {
      title: 'Обращения',
      roles: ['appeal-show', 'appeal-edit']
    },
    children: [
      {
        path: 'list',
        name: 'adminAppealsList',
        component: () => import('src/Modules/Appeal/pages/AppealList/index.vue'),
        hidden: true,
        meta: {
          title: 'Обращения',
          roles: ['appeal-show', 'appeal-edit']
        }
      },
      {
        path: 'show/:id',
        component: () => import('src/Modules/Appeal/pages/AppealShow/index.vue'),
        hidden: true,
        meta: {
          title: 'Обращения',
          roles: ['appeal-show', 'appeal-edit']
        }
      }
    ]
  }
export const appeal =
  {
    path: '/appeal',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'show/:id',
        component: () => import('src/Modules/Appeal/pages/AppealShow/index.vue'),
        meta: {
          title: 'Обращения'
        }
      }
    ]
  }
