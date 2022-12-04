/** When your routing table is too long, you can split it into small modules**/

const voting =
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
export default voting
