/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
//import vuetify from "./plugins/vuetify";

require('./bootstrap');
import Element from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
// import moment from 'vue-moment'
const moment = require('moment-timezone');
require('moment/locale/es');
moment.locale('ru');
moment.tz.setDefault('UTC')

window.Vue = require('vue')
Vue.use(Element)

Vue.use(require('vue-moment'), {
    moment
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('chart-vue', require('./components/ChartTemperComponent.vue').default);
Vue.component('show-local-temper-now', require('./components/ShowTemperNowComponent').default);
Vue.component('yandex-rasp-vue', require('./components/YandexRaspComponent.vue').default);
Vue.component('user-profile', require('./components/user/UserProfileComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
});
