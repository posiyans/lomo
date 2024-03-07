export const adminSettingsRouter = {
  path: 'settings',
  meta: {
    icon: 'settings',
    title: 'Настройки',
    roles: ['site-menu-edit', 'settings-gardening', 'settings-camera', 'access-admin-panel']
  },
  redirect: { name: 'globalSettings' },
  children: [
    {
      path: 'global',
      name: 'globalSettings',
      component: () => import('src/Modules/Settings/pages/PrimarySettings/index.vue'),
      meta: {
        icon: 'menu',
        title: 'Общие настройки',
        roles: ['access-admin-panel']
      }
    },
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
        title: 'Реквизиты',
        roles: ['settings-gardening']
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
