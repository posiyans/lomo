export const adminSettingsRouter = {
  path: 'settings',
  meta: {
    icon: 'settings',
    title: 'Настройки',
    roles: ['site-menu-edit', 'settings-gardening', 'settings-rate', 'settings-camera']
  },
  redirect: { name: 'EditSiteMenu' },
  children: [
    {
      path: 'menu',
      name: 'EditSiteMenu',
      component: () => import('src/Modules/SiteMenu/pages/EditSiteMenu/index.vue'),
      meta: {
        icon: 'menu',
        title: 'Меню сайта',
        roles: ['site-menu-edit']
      }
    },
    {
      path: 'gardening',
      component: () => import('src/Modules/Gardening/pages/GardeningSetting/index.vue'),
      meta: {
        icon: 'info',
        title: 'Настройки',
        roles: ['settings-gardening']
      }
    },
    {
      path: 'rate',
      component: () => import('src/Modules/Rates/pages/SettingsRates/index.vue'),
      meta: {
        icon: 'star_rate',
        title: 'Тарифы',
        roles: ['settings-rate']
      }
    },
    {
      path: 'camera',
      component: () => import('src/Modules/Camera/page/CameraSetting/index.vue'),
      meta: {
        icon: 'photo_camera',
        title: 'Камеры',
        roles: ['settings-camera']
      }
    }
  ]

}