<template>
  <el-container id="main-container">
    <el-container>
      <el-header style="text-align: right; font-size: 12px">
        <el-row
          align="middle"
          type="flex"
          justify="space-between"
          class="layout-header"
        >
          <el-col :md="6"  :xs="8">
            <div class="header logo">ЛОМО</div>
          </el-col>
          <el-col :md="6" :xs="16">
            <HeaderLink/>
          </el-col>
        </el-row>
      </el-header>
      <el-row>
        <el-col :span="24">
          <ItemMenu :menu="menu"/>
        </el-col>
      </el-row>
      <el-main class="main-body">
        <el-container>
          <el-main class="main-app">
            <app-main/>
          </el-main>
          <el-aside v-if="asideShow">
            <LoginForm></LoginForm>
            <ReceiptForm></ReceiptForm>
            <WeatherForm></WeatherForm>
          </el-aside>
        </el-container>
      </el-main>
      <el-footer class="el-header">
        <div align="center">
          <div class="el-link" @click="showPersonal"> Внимание!!! Оставаясь на данном сайте вы соглашаетесь с политикой обработки пересональных данных</div>

        </div>
      </el-footer>
    </el-container>
  </el-container>
</template>

<script>
import RightPanel from '@/components/RightPanel'
import HeaderLink from './components/HeaderLink/index.vue'
import RightCard from '@/components/RightCard/index.vue'
import ReceiptForm from '@/components/Module/ReceiptForm/index.vue'
import WeatherForm from '@/components/Module/WeatherForm/index.vue'
import LoginForm from '@/components/LoginForm'
import ItemMenu from './menu/ItemMenu.vue'
import { AppMain, Navbar, Settings, Sidebar, TagsView } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import { mapState, mapGetters } from 'vuex'
import store from '../../store'

export default {
  name: 'Layout',
  components: {
    AppMain,
    HeaderLink,
    Navbar,
    ItemMenu,
    RightPanel,
    WeatherForm,
    LoginForm,
    ReceiptForm,
    RightCard,
    Settings,
    Sidebar,
    TagsView
  },
  mixins: [ResizeMixin],
  data() {
    return {
      activeIndex: '1',
      form: {
        name: ''
      },
      menus: [
        {
          route: '/dashboard',
          name: 'Главная',
          icon: 'el-icon-s-custom'
        },
        {
          route: '/documentation',
          name: 'Личный кабинет'
        },
        {
          route: '/icon',
          name: 'Новости'
        },
        {
          route: '/guide',
          name: 'Написать в правление'
        },
        {
          route: '/sdfsdf5',
          name: 'Голосование'
        },
        {
          route: '/sdfsdf6',
          name: 'Объявления'
        },
        {
          route: '/sdfsdf7',
          name: 'Контакты'
        }

      ]
    }
  },
  mounted() {
    // console.log(this.$route)
    // console.log('this.menu')
    // console.log(this.menu)
    // console.log(this.$store.permission_routes)
    // console.log(this.$store)
  },
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      device: state => state.app.device,
      showSettings: state => state.settings.showSettings,
      needTagsView: state => state.settings.tagsView,
      fixedHeader: state => state.settings.fixedHeader
    }),
    ...mapGetters([
      'menu'
    ]),
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile'
      }
    },
    asideShow() {
      if (this.$route.fullPath !== '/index') {
        return false
      }
      if (this.device === 'mobile'){
        return false
      }
      return true
    },
    // screenResize() {
    //   return window.screen.width
    // },
    path() {
      return this.$route.fullPath
    }
  },
  methods: {
    showPersonal(){
      this.$router.push('/article/show/term-of-use')
      // this.$router.push('/article/show/10')
    },
    handleSelect(key, keyPath) {

      // console.log(this.$router)
      this.$router.push(key)
      // console.log(key, keyPath)
    },
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    }
  },
  watch:{
    // screenResize: {
    //   return
    // }
  }
}
</script>

<style scoped>
  .main-body{
    padding: 5px 0;
  }
 /*.header {*/
 /*  line-height: 60px;*/
 /*}*/
 /* .layout-header {*/
 /*   background-color: #00084c;*/
 /*   color: #fff;*/
 /* }*/

</style>

