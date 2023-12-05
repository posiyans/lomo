export const adminComment =
  {
    path: 'comment',
    meta: {
      icon: 'message',
      title: 'Комментарии',
      roles: ['comment-edit', 'comment-delete']
    },
    redirect: { name: 'adminCommentList' },
    children: [
      {
        path: 'list',
        name: 'adminCommentList',
        component: () => import('src/Modules/Comments/pages/AdminCommentList/index.vue'),
        hidden: true,
        meta: {
          icon: 'article',
          title: 'Все комментарии',
          roles: ['comment-edit', 'comment-delete']
        }
      },
      {
        path: 'show/:uid',
        component: () => import('src/Modules/Users/pages/AdminUserInfo/index.vue'),
        hidden: true,
        meta: {
          icon: 'people',
          title: 'Комментарий',
          roles: ['comment-edit', 'comment-delete']
        }
      }
    ]
  }
