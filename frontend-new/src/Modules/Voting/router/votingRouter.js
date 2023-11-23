/** When your routing table is too long, you can split it into small modules**/

export const voting =
  {
    path: '/voting',
    component: () => import('layouts/UserLayout/index.vue'),
    children: [
      {
        path: 'index',
        component: () => import('src/Modules/Voting/page/UserVotingList/index.vue'),
        hidden: true,
        meta: {
          title: 'Голосования'
        }
      },
      {
        path: 'show/:id',
        component: () => import('src/Modules/Voting/page/UserVotingShow/index.vue'),
        hidden: true,
        meta: {
          title: 'Голосование'
        }
      }
      // {
      //   path: 'send-file/:id',
      //   component: () => import('src/Modules/Voting/page/SendVoteFile/index.vue'),
      //   hidden: true,
      //   meta: {
      //     title: 'Голосование'
      //   }
      // }
    ]
  }
export const adminVoting = {
  path: 'voting',
  meta: {
    title: 'Голосование',
    roles: ['voting-show', 'voting-edit']
  },
  redirect: { name: 'adminVotingList' },
  children: [
    {
      path: 'edit/',
      component: () => import('src/Modules/Article/Article/page/EditArticle/index.vue'),
      meta: {
        title: 'Добавить',
        roles: ['voting-edit']
      }
    },
    {
      path: 'edit/:uid',
      component: () => import('src/Modules/Article/Article/page/EditArticle/index.vue'),
      hidden: true,
      meta: {
        title: 'Редактировать',
        roles: ['voting-edit']
      }
    },
    {
      path: 'list',
      name: 'adminVotingList',
      component: () => import('src/Modules/Voting/page/AdminVotingList/index.vue'),
      meta: {
        title: 'Голосование',
        roles: ['voting-show']
      }
    }
  ]
}
