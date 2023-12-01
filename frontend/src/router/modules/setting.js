/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout/AdminLayout'

const settingRouter = {

  path: '/admin/settings',
  component: Layout,
  redirect: '/admin/settings/menu',
  meta: {
    title: 'Настройки',
    icon: 'example'
  },
  children: [
    {
      path: 'menu',
      component: () => import('@/views/admin/settings/MenuSite/index'),
      name: 'MenuSite',
      meta: {
        title: 'Меню сайта',
        icon: 'documentation',
        affix: true,
        roles: ['gardening-edit']
      }
    },
    {
      path: 'gardening',
      component: () => import('@/views/admin/settings/Gardenings/index'),
      name: 'Settings',
      meta: {
        title: 'Настройки',
        icon: 'documentation',
        affix: true,
        roles: ['gardening-edit']
      }
    },
    {
      path: 'rate',
      component: () => import('@/views/admin/settings/Rates/index'),
      name: 'RateModel',
      meta: {
        title: 'Тарифы',
        icon: 'documentation',
        affix: true,
        roles: ['edit-rate']
      }
    },
    {
      path: 'camera',
      component: () => import('@/views/admin/settings/CameraSetting/index'),
      name: 'CameraSetting',
      meta: {
        title: 'Камеры',
        icon: 'documentation',
        affix: true
      }
    }
  ]

}

export default settingRouter
