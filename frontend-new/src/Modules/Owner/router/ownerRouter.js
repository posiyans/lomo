export const owner =
  {}
export const adminOwner =
  {
    path: 'owner/list',
    name: 'adminOwnerList',
    component: () => import('src/Modules/Owner/pages/OwnerList/index.vue'),
    meta: {
      icon: 'groups',
      title: 'Собственники',
      roles: ['owner-show', 'owner-edit']
    }
  }
