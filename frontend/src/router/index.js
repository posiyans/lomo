import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout/AdminLayout'
// import AdminLayout from '@/layout'
import UserLayout from '@/layout/UserLayout'

/* Router Modules */
// import componentsRouter from './modules/components'
// import chartsRouter from './modules/charts'
import bookkepingRouter from './modules/bookkeeping'
import settingRouter from './modules/setting'
import allInstrumentReading from './modules/all_instrument_reading'
import owner from './modules/owner'

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
    affix: true                  if set true, the tag will affix in the tags-view (если установлено true, тег будет добавлен в теги-view )
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
    path: '/admin',
    component: Layout,
    redirect: '/admin/my-dashboard',
    children: [
      {
        path: 'my-dashboard',
        component: () => import('@/views/admin/dashboard/admin/index'),
        name: 'AdminDashboard',
        meta: { title: 'Главная', icon: 'dashboard', affix: true }
      }
    ]
  },
  {
    path: '/temper',
    component: Layout,
    redirect: '/modules/weather'
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
        meta: { title: 'Сайт', icon: 'link', affix: true }
      },
      allInstrumentReading
    ]
  },
  {
    path: '/article',
    component: UserLayout,
    redirect: '/article/list',
    children: [
      {
        path: 'list/:id?',
        component: () => import('@/views/article-list/index'),
        name: 'ArticleList',
        hidden: true
      },
      {
        path: 'show/:id',
        component: () => import('@/views/article-view/index'),
        name: 'Article',
        hidden: true
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
      {
        path: 'camera',
        component: () => import('@/views/modules/camera/index.vue'),
        name: 'modulesCamera',
        hidden: true
      },
      {
        path: 'schedule',
        component: () => import('@/views/modules/YandexSchedule/index.vue'),
        name: 'modulesSchedule',
        hidden: true
      },
      {
        path: 'yandexmap',
        component: () => import('@/views/yandex/map/index.vue'),
        name: 'yandexMap',
        hidden: true
      }
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
      }
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
      }
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
        name: 'UserVotingList',
        hidden: true
      },
      {
        path: 'show/:id',
        component: () => import('@/views/user/voting/VotingShow/index'),
        name: 'UserVotingShow',
        hidden: true
      },
      {
        path: 'send-file/:id',
        component: () => import('@/views/user/voting/SendFiles/index'),
        hidden: true
      }
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
      }
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
      }
    ]
  },
  allInstrumentReading,
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
      }
      // {
      //   path: 'category',
      //   component: () => import('@/views/admin/article/category/list'),
      //   name: 'CategoryList',
      //   meta: { title: 'Категории', icon: 'list' }
      // }
    ]
  },

  {
    path: '/admin/steads',
    component: Layout,
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/steads/SteadList/index'),
        name: 'Steads',
        meta: { title: 'Участки', icon: 'documentation', affix: true }
      }
    ]
  }

]

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [
  owner,
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
          roles: ['сreate-polls']
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
          roles: ['сreate-polls']
        },
        hidden: true
      },
      {
        path: 'addAnswer/:id(\\d+)',
        component: () => import('@/views/admin/voting/result/Owner/user-answer/add/'),
        name: 'AdminEditVotingAddAnswer',
        meta: {
          title: 'Правка',
          noCache: true,
          activeMenu: '/voting/list',
          roles: ['сreate-polls']
        },
        hidden: true
      },
      {
        path: 'result/:id(\\d+)',
        component: () => import('@/views/admin/voting/result/index'),
        name: 'AdminResultVoting',
        meta: {
          title: 'Результаты',
          noCache: true,
          activeMenu: '/voting/list'
        },
        hidden: true
      }
    ]
  },
  {
    path: '/users',
    component: Layout,
    title: 'Пользователи',
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/users/list/index'),
        name: 'UsersList',
        meta: {
          title: 'Пользователи',
          icon: 'documentation',
          affix: true,
          roles: ['show-users']
        }
      },
      {
        path: 'show/:id(\\d+)',
        component: () => import('@/views/admin/users/show/index'),
        name: 'UserShow',
        hidden: true,
        meta: {
          affix: true,
          roles: ['show-users']
        }
      }
    ]
  },
  // {
  //   path: '/mail',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/clear/index'),
  //       name: 'Mail',
  //       meta: {
  //         title: 'Рассылка',
  //         icon: 'documentation',
  //         affix: true,
  //         roles: ['send-mail-spam']
  //       }
  //     }
  //   ]
  // },
  bookkepingRouter,
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
  settingRouter,
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
  mode: 'history', // require service support
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
