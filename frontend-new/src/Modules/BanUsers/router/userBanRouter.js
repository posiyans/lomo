// export const article =
//   {
//     path: '/user/ban',
//     component: () => import('layouts/UserLayout/index.vue'),
//     children: [
//       {
//         path: 'show/:uid',
//         component: () => import('src/Modules/Article/Article/page/Show/index.vue'),
//         hidden: true,
//         meta: {}
//       },
//       {
//         path: 'list/:uid',
//         component: () => import('src/Modules/Article/Article/page/List/index.vue'),
//         hidden: true,
//         meta: {}
//       },
//       {
//         path: 'add-news',
//         component: () => import('src/Modules/Article/Article/page/UserOfferNews/index.vue'),
//         hidden: true,
//         meta: { title: 'Предложить новость' }
//       }
//     ]
//   }
export const adminUserBan =
  {
    path: 'user-ban',
    meta: {
      icon: 'block',
      title: 'Бан',
      roles: ['comment-ban']
    },
    redirect: { name: 'adminUserBanList' },
    children: [
      {
        path: 'list',
        name: 'adminUserBanList',
        component: () => import('src/Modules/BanUsers/pages/BanUserList/index.vue'),
        meta: {
          icon: 'list_alt',
          title: 'Черный список',
          roles: ['comment-ban']
        }
      }
    ]
  }
