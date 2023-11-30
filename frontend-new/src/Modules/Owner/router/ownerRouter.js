export const owner =
  {}
export const adminOwner =
  {
    path: 'owner',
    redirect: { name: 'adminOwnerList' },
    meta: {
      icon: 'groups',
      title: 'Собственники',
      roles: ['owner-show', 'owner-edit']
    },
    children: [
      {
        path: 'list',
        name: 'adminOwnerList',
        component: () => import('src/Modules/Owner/pages/OwnerList/index.vue'),
        meta: {
          icon: 'groups',
          title: 'Собственники',
          roles: ['owner-show', 'owner-edit']
        }
      },
      {
        path: 'add',
        name: 'adminAddOwner',
        component: () => import('src/Modules/Owner/pages/AddOwner/index.vue'),
        hidden: true,
        meta: {
          icon: 'groups',
          title: 'Добавить собственника',
          roles: ['owner-edit']
        }
      },
      {
        path: 'show-info/:uid',
        name: 'OwnerInfo',
        component: () => import('src/Modules/Owner/pages/OwnerInfo/index.vue'),
        hidden: true,
        meta: {
          icon: 'groups',
          title: 'Собственник',
          roles: ['owner-show', 'owner-edit']
        }
      }
    ]
  }
