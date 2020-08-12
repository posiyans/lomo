import Vue from 'vue'

import Cookies from 'js-cookie'

import 'normalize.css/normalize.css' // a modern alternative to CSS resets

import Element from 'element-ui'
import locale from 'element-ui/lib/locale/lang/ru-RU'
import './styles/element-variables.scss'
import 'element-ui/lib/theme-chalk/display.css'

import '@/styles/index.scss' // global css
import '@/styles/global2.scss' // global css
import '@/styles/tachyons.css' // global css


import App from './App'
import store from './store'
import router from './router'

import './icons' // icon
import './permission' // permission control
import './utils/error-log' // error log
const moment = require('moment-timezone')
require('moment/locale/ru')
moment.locale('ru')
moment.tz.setDefault('Europe/Moscow')

import * as filters from './filters' // global filters
import YmapPlugin from 'vue-yandex-maps'

const settings = {
  apiKey: process.env.VUE_APP_YANDEX_API_MAP,
  lang: 'ru_RU',
  coordorder: 'latlong',
  version: '2.1' }

Vue.use(YmapPlugin, settings)
/**
 * If you don't want to use mock-server
 * you want to use MockJs for mock api
 * you can execute: mockXHR()
 *
 * Currently MockJs will be used in the production environment,
 * please remove it before going online ! ! !
 */
// if (process.env.NODE_ENV === 'production') {
//   const { mockXHR } = require('../mock')
//   mockXHR()
// }

Vue.use(Element, {
  size: Cookies.get('size') || 'medium', // set element-ui default size.
  locale
})

Vue.use(require('vue-moment'), {
  moment
});
// register global utility filters
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key])
})

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
