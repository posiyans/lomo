export const adminAppeal =
  {
    path: 'appeals/list',
    name: 'adminAppealsList',
    component: () => import('src/Modules/Appeal/pages/AppealList/index.vue'),
    meta: {
      title: 'Обращения',
      roles: ['appeal-show', 'appeal-edit']
    }
  }
