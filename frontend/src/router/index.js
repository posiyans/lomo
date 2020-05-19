import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'
import AdminLayout from '@/layout'
import UserLayout from '@/layout/UserLayout'

/* Router Modules */
// import componentsRouter from './modules/components'
// import chartsRouter from './modules/charts'
// import tableRouter from './modules/table'
// import nestedRouter from './modules/nested'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if set true, the page will no be cached(default is false)
    affix: true                  if set true, the tag will affix in the tags-view
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path(.*)',
        component: () => import('@/views/redirect/index')
      }
    ]
  },
  {
    path: '/login',
    component: UserLayout,
    redirect: '/login/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/login/index'),
        hidden: true
      }
    ]
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/auth-redirect'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/error-page/404'),
    hidden: true
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true
  },
  {
    path: '/',
    component: UserLayout,
    redirect: '/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/main-page/index'),
        name: 'Dashboard',
        meta: { title: 'Сайт', icon: 'dashboard', affix: true }
      }
    ]
  },
  {
    path: '/modules',
    component: UserLayout,
    redirect: '/modules/rates',
    children: [
      {
        path: 'rates',
        component: () => import('@/views/modules/rates/index'),
        name: 'modulesRates',
        hidden: true
      },
      {
        path: 'receipt',
        component: () => import('@/views/modules/ReceiptForm/index.vue'),
        name: 'modulesReceipt',
        hidden: true
      },
      {
        path: 'weather',
        component: () => import('@/views/modules/weatherPro/index.vue'),
        name: 'modulesWeather',
        hidden: true
      },
    ]
  },
  {
    path: '/user',
    component: UserLayout,
    redirect: '/user/profile',
    children: [
      {
        path: 'profile',
        component: () => import('@/views/user/profile/index'),
        name: 'UserProfile',
        hidden: true
      },
    ]
  },
  {
    path: '/receipt',
    component: UserLayout,
    redirect: '/receipt/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/user/receipt/index'),
        name: 'Receipt',
        hidden: true
      },
    ]
  },
  {
    path: '/voting',
    component: UserLayout,
    redirect: '/voting/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/user/voting/index'),
        name: 'Voting',
        hidden: true
      },
    ]
  },
  {
    path: '/weather',
    component: UserLayout,
    redirect: '/weather/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/weather/index'),
        name: 'Wather',
        hidden: true
      },
    ]
  },
  {
    path: '/password',
    component: UserLayout,
    redirect: '/password/reset',
    children: [
      {
        path: 'reset',
        component: () => import('@/views/password-reset/index'),
        name: 'PasswordReset',
        hidden: true
      },
      {
        path: 'change',
        component: () => import('@/views/password-change/index'),
        name: 'PasswordChange',
        hidden: true
      },
    ]
  },
  // {
  //   path: '/dash',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/dashboard/index'),
  //       name: 'Dashboard',
  //       meta: { title: 'Dashboard', icon: 'dashboard', affix: true }
  //     }
  //   ]
  // },
  {
    path: '/admin-article',
    component: Layout,
    redirect: '/admin-article/list',
    name: 'AdminArticle',
    meta: {
      title: 'Статьи',
      icon: 'example'
    },
    children: [
      {
        path: 'create',
        component: () => import('@/views/admin/article/create'),
        name: 'AdminCreateArticle',
        meta: { title: 'Добавить статью', icon: 'edit' }
      },
      {
        path: 'edit/:id(\\d+)',
        component: () => import('@/views/admin/article/edit'),
        name: 'AdminEditArticle',
        meta: { title: 'Правка', noCache: true, activeMenu: '/admin-article/list' },
        hidden: true
      },
      {
        path: 'list',
        component: () => import('@/views/admin/article/index'),
        name: 'AdminArticleList',
        meta: { title: 'Статьи', icon: 'list' }
      },
      {
        path: 'category',
        component: () => import('@/views/admin/article/category/list'),
        name: 'CategoryList',
        meta: { title: 'Категории', icon: 'list' }
      }
    ]
  },

  {
    path: '/steads',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/steads/index'),
        name: 'Steads',
        meta: { title: 'Участки', icon: 'documentation', affix: true }
      }
    ]
  },

]

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [
  {
    path: '/appeals',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/appeals/index'),
        name: 'Appeal',
        meta: {
          title: 'Обращения',
          icon: 'documentation',
          affix: true,
          roles: ['show-appels']
        }
      }
    ]
  },
  {
    path: '/admin/voting',
    component: Layout,
    redirect: '/admin/voting/index',
    meta: {
      title: 'Голосование',
      icon: 'example'
    },
    children: [
      {
        path: 'create',
        component: () => import('@/views/admin/voting/create'),
        name: 'AdminVotingCreate',
        meta: {
          title: 'Добавить',
          icon: 'edit',
          roles: ['sсreate-polls']
        }
      },
      {
        path: 'index',
        component: () => import('@/views/admin/voting/index'),
        name: 'AdminVoting',
        meta: { title: 'Голосование', icon: 'documentation', affix: true }
      },
      {
        path: 'edit/:id(\\d+)',
        component: () => import('@/views/admin/voting/edit'),
        name: 'AdminEditVoting',
        meta: {
          title: 'Правка',
          noCache: true,
          activeMenu: '/voting/list',
          roles: ['sсreate-polls']
        },
        hidden: true
      },
      {
        path: 'result/:id(\\d+)',
        component: () => import('@/views/admin/voting/result'),
        name: 'AdminResultVoting',
        meta: {
          title: 'Правка',
          noCache: true,
          activeMenu: '/voting/list'
        },
        hidden: true
      },
    ]
  },
  {
    path: '/users',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/users/index'),
        name: 'Users',
        meta: {
          title: 'Пользователи',
          icon: 'documentation',
          affix: true,
          roles: ['show-users']
        }
      }
    ]
  },
  {
    path: '/mail',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/clear/index'),
        name: 'Mail',
        meta: {
          title: 'Рассылка',
          icon: 'documentation',
          affix: true,
          roles: ['send-mail-spam']
        }
      }
    ]
  },
  {
    path: '/rate',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/rates/index'),
        name: 'Rate',
        meta: {
          title: 'Тарифы',
          icon: 'documentation',
          affix: true,
          roles: ['edit-rate']
        }
      }
    ]
  },
  {
    path: '/permissions',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/permission/index'),
        name: 'permissions',
        meta: {
          title: 'Права доступа',
          icon: 'documentation',
          roles: ['edit-role']
        }
      }
    ]
  },
  {
    path: '/settings',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/settings/index'),
        name: 'Settings',
        meta: {
          title: 'Настройки',
          icon: 'documentation',
          affix: true,
          roles: ['gardening-edit'] }
      }
    ]
  },
  // {
  //   path: '/permission',
  //   component: Layout,
  //   redirect: '/permission2/page',
  //   alwaysShow: true, // will always show the root menu
  //   name: 'Permission',
  //   meta: {
  //     title: 'Permission5',
  //     icon: 'lock',
  //     roles: ['ladmin', 'editor'] // you can set roles in root nav
  //   },
  //   children: [
  //     {
  //       path: 'page',
  //       component: () => import('@/views/permission2/page'),
  //       name: 'PagePermission',
  //       meta: {
  //         title: 'Page Permission2',
  //         roles: ['admin'] // or you can only set roles in sub nav
  //       }
  //     },
  //     {
  //       path: 'directive',
  //       component: () => import('@/views/permission2/directive'),
  //       name: 'DirectivePermission',
  //       meta: {
  //         title: 'Directive Permission'
  //         // if do not set roles, means: this page does not require permission
  //       }
  //     },
  //     {
  //       path: 'role',
  //       component: () => import('@/views/permission2/role'),
  //       name: 'RolePermission',
  //       meta: {
  //         title: 'Role Permission',
  //         roles: ['admin']
  //       }
  //     }
  //   ]
  // },
  //
  // {
  //   path: '/icon',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/icons/index'),
  //       name: 'Icons',
  //       meta: { title: 'Icons', icon: 'icon', noCache: true, roles: ['access-admin-panel'] }
  //     }
  //   ]
  // },

  /** when your routing map is too long, you can split it into small modules **/
  // componentsRouter,
  // chartsRouter,
  // nestedRouter,
  // tableRouter,

  // {
  //   path: '/example',
  //   component: Layout,
  //   redirect: '/example/list',
  //   name: 'Example',
  //   meta: {
  //     title: 'Example',
  //     icon: 'example'
  //   },
  //   children: [
  //     {
  //       path: 'create',
  //       component: () => import('@/views/example/create'),
  //       name: 'CreateArticle',
  //       meta: { title: 'Create Article', icon: 'edit' }
  //     },
  //     {
  //       path: 'edit/:id(\\d+)',
  //       component: () => import('@/views/example/edit'),
  //       name: 'EditArticle',
  //       meta: { title: 'Edit Article', noCache: true, activeMenu: '/example/list' },
  //       hidden: true
  //     },
  //     {
  //       path: 'list',
  //       component: () => import('@/views/example/list'),
  //       name: 'ArticleList',
  //       meta: { title: 'Article List', icon: 'list' }
  //     }
  //   ]
  // },

  // {
  //   path: '/tab',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/tab/index'),
  //       name: 'Tab',
  //       meta: { title: 'Tab', icon: 'tab' }
  //     }
  //   ]
  // },

  // {
  //   path: '/error',
  //   component: Layout,
  //   redirect: 'noRedirect',
  //   name: 'ErrorPages',
  //   meta: {
  //     title: 'Error Pages',
  //     icon: '404'
  //   },
  //   children: [
  //     {
  //       path: '401',
  //       component: () => import('@/views/error-page/401'),
  //       name: 'Page401',
  //       meta: { title: '401', noCache: true }
  //     },
  //     {
  //       path: '404',
  //       component: () => import('@/views/error-page/404'),
  //       name: 'Page404',
  //       meta: { title: '404', noCache: true }
  //     }
  //   ]
  // },

  // {
  //   path: '/error-log',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'log',
  //       component: () => import('@/views/error-log/index'),
  //       name: 'ErrorLog',
  //       meta: { title: 'Error Log', icon: 'bug' }
  //     }
  //   ]
  // },
  //
  // {
  //   path: '/excel',
  //   component: Layout,
  //   redirect: '/excel/export-excel',
  //   name: 'Excel',
  //   meta: {
  //     title: 'Excel',
  //     icon: 'excel'
  //   },
  //   children: [
  //     {
  //       path: 'export-excel',
  //       component: () => import('@/views/excel/export-excel'),
  //       name: 'ExportExcel',
  //       meta: { title: 'Export Excel' }
  //     },
  //     {
  //       path: 'export-selected-excel',
  //       component: () => import('@/views/excel/select-excel'),
  //       name: 'SelectExcel',
  //       meta: { title: 'Export Selected' }
  //     },
  //     {
  //       path: 'export-merge-header',
  //       component: () => import('@/views/excel/merge-header'),
  //       name: 'MergeHeader',
  //       meta: { title: 'Merge Header' }
  //     },
  //     {
  //       path: 'upload-excel',
  //       component: () => import('@/views/excel/upload-excel'),
  //       name: 'UploadExcel',
  //       meta: { title: 'Upload Excel' }
  //     }
  //   ]
  // },
  //
  // {
  //   path: '/zip',
  //   component: Layout,
  //   redirect: '/zip/download',
  //   alwaysShow: true,
  //   name: 'Zip',
  //   meta: { title: 'Zip', icon: 'zip' },
  //   children: [
  //     {
  //       path: 'download',
  //       component: () => import('@/views/zip/index'),
  //       name: 'ExportZip',
  //       meta: { title: 'Export Zip' }
  //     }
  //   ]
  // },
  //
  // {
  //   path: '/pdf',
  //   component: Layout,
  //   redirect: '/pdf/index',
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/pdf/index'),
  //       name: 'PDF',
  //       meta: { title: 'PDF', icon: 'pdf' }
  //     }
  //   ]
  // },
  // {
  //   path: '/pdf/download',
  //   component: () => import('@/views/pdf/download'),
  //   hidden: true
  // },
  //
  // {
  //   path: '/theme',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/theme/index'),
  //       name: 'Theme',
  //       meta: { title: 'Theme', icon: 'theme' }
  //     }
  //   ]
  // },
  //
  // {
  //   path: '/clipboard',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/clipboard/index'),
  //       name: 'ClipboardDemo',
  //       meta: { title: 'Clipboard', icon: 'clipboard' }
  //     }
  //   ]
  // },
  //
  // {
  //   path: 'external-link',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'https://github.com/PanJiaChen/vue-element-admin',
  //       meta: { title: 'External Link', icon: 'link' }
  //     }
  //   ]
  // },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
