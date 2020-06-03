<template>
  <el-container id="main-container">
    <el-container>
      <el-header style="text-align: right; font-size: 12px">
        <el-row
          align="left"
          type="flex"
          justify="space-between"
          class="layout-header"
        >
          <el-col :md="6"  :xs="12">
            <hamburger v-if="mobile" :is-active="open_sidebar" class="hamburger-container" @toggleClick="toggleSideBar" />
            <div class="header logo">
              {{site_name}}
              <TemperWidget/>
            </div>
          </el-col>
          <el-col :md="6" :xs="12">
            <HeaderLink/>
          </el-col>
        </el-row>
      </el-header>
      <el-row v-if="open_sidebar || !mobile" >
        <el-col :span="24">
          <ItemMenu :menu="menu" @toggleClick="open_sidebar = false" />
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
      <el-footer class="footer">
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
import Hamburger from '@/components/Hamburger'
import { AppMain, Navbar, Settings, Sidebar, TagsView } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import { mapState, mapGetters } from 'vuex'
import TemperWidget from './components/TemperWidget'
import store from '../../store'

export default {
  name: 'Layout',
  components: {
    TemperWidget,
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
    Hamburger,
    TagsView
  },
  mixins: [ResizeMixin],
  data() {
    return {
      activeIndex: '1',
      form: {
        name: ''
      },
      site_name: process.env.VUE_APP_SITE_NAME,
      open_sidebar: false
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
      'sidebar',
      'user_sidebar',
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
      if (this.device === 'mobile') {
        return false
      }
      return true
    },
    mobile() {
      // if (this.$route.fullPath !== '/index') {
      //   return false
      // }
      if (this.device === 'mobile') {
        return true
      }
      return false
    },
    // screenResize() {
    //   return window.screen.width
    // },
    path() {
      return this.$route.fullPath
    }
  },
  methods: {
    toggleSideBar() {
      this.open_sidebar = !this.open_sidebar
    },
    showPersonal() {
      this.$router.push('/article/show/term-of-use')
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
  .footer {
    background-color: #AFEEEE;
    color:#333;
    padding: 10px 0;
  }

 /*.header {*/
 /*  line-height: 60px;*/
 /*}*/
 /* .layout-header {*/
 /*   background-color: #00084c;*/
 /*   color: #fff;*/
 /* }*/
  .hamburger-container {
    margin-top: 5px;
    margin-bottom: 5px;
    margin-right: 10px;
    line-height: 48px;
    height: 50px;
    float: left;
    cursor: pointer;
    transition: background .3s;
    background-color: #79e5e5;;
    border-radius: 10px;
    -webkit-tap-highlight-color:transparent;

  &:hover {
     background: rgba(0, 0, 0, .005)
   }
  }

</style>

