/* eslint-disable */
import { authUser } from 'src/Modules/Auth/router/auth'
import { article } from 'src/Modules/Article/router/article'
import rates from 'src/Modules/Rates/router/rates'
import receipt from 'src/Modules/Receipt/router/receipt.js'
import yandex from 'src/Modules/Yandex/router/yandex.js'
import camera from 'src/Modules/Camera/router/camera.js'
import weather from 'src/Modules/Weather/router/weather.js'
import { voting } from 'src/Modules/Voting/router/votingRouter.js'
import adminRoutes from './adminRoutes.js'
import { personalAreaRouter } from 'src/Modules/PersonalArea/router/personalAreaRouter'
import { appeal } from 'src/Modules/Appeal/router/appealRouter'

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        component: () => import('layouts/UserLayout/index.vue'),
        children: [
          { path: '', component: () => import('src/pages/MainPage/index.vue') }
        ]
      },
      personalAreaRouter,
      authUser,
      article,
      rates,
      receipt,
      yandex,
      camera,
      weather,
      voting,
      appeal
    ]
  },
  {
    path: '/admin',
    component: () => import ('layouts/AdminLayout/index.vue'),
    meta: {
      roles: ['access-admin-panel']
    },
    children: adminRoutes

  },
  {
    path: '/:catchAll(.*)*',
    // component: () => import('pages/ErrorNotFound.vue')
    component: () => import('src/pages/Errors/404.vue')
  }
]


export const asyncRoutes = [
  {
    path: '/new-admin',
    component: () => import('layouts/AdminLayout/index.vue'),
    meta: {},
    children: [
      {
        path: '',
        component: () => import('src/pages/AdminNew/index.vue')
      },
      // asyncUser,
      // asyncAdmin,
    ]
  }
]

export default routes
