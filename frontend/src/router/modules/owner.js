/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout/AdminLayout'

const owner = {
  path: '/admin/owner',
  component: Layout,
  redirect: '/admin/owner/show-list',
  children: [
    {
      path: 'show-list',
      component: () => import('@/views/admin/owner/OwnerList/index'),
      name: 'AdminOwner',
      meta: {
        title: 'Собственники',
        icon: 'documentation',
        affix: true,
        roles: ['edit-rate']
        // roles: ['access-to-personal'] // todo поменять
      }
    },
    {
      path: 'show-info/:id(\\d+)',
      component: () => import('@/views/admin/owner/ShowOwnerInfo/index'),
      hidden: true
    },
    {
      path: 'add',
      component: () => import('@/views/admin/owner/AddOwner/index'),
      hidden: true
    }
  ]
}

export default owner
