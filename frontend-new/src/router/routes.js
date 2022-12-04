/* eslint-disable */
import { authUser, asyncUser } from 'src/Modules/User/router/auth'
import { article, asyncArticle } from 'src/Modules/Article/router/article'
import rates from 'src/Modules/Rates/router/rates'
import receipt from 'src/Modules/Receipt/router/receipt.js'
import yandex from 'src/Modules/Yandex/router/yandex.js'
import camera from 'src/Modules/Camera/router/camera.js'
import weather from 'src/Modules/Weather/router/weather.js'
import voting from 'src/Modules/Voting/router/voting.js'

import { asyncAdmin } from 'src/Modules/Admin/router/admin.js'

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
      authUser,
      article,
      rates,
      receipt,
      yandex,
      camera,
      weather,
      voting
    ]
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
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
      asyncArticle
      // asyncUser,
      // asyncAdmin,
    ]
  }
]

export default routes
