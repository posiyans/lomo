<template>
  <el-container id="main-container">
    <el-container>
      <div class="main-header">
        <hamburger v-if="mobile" :is-active="open_sidebar" class="hamburger-container" @toggleClick="toggleSideBar" />
        <div
          class="header-block site-title"
        >
          {{ site_name }}
        </div>
        <div
          class="header-block"
        >
          <TemperWidget />
        </div>
        <div
          class="header-link"
        >
          <HeaderLink />
        </div>
      </div>
      <el-row v-if="open_sidebar || !mobile">
        <el-col :span="24">
          <ItemMenu :menu="menu" @toggleClick="open_sidebar = false" />
        </el-col>
      </el-row>
      <el-main class="main-body">
        <el-container>
          <el-main class="main-app">
            <app-main />
          </el-main>
          <el-aside v-if="asideShow">
            <LoginForm />
            <ReceiptForm />
            <WeatherForm />
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
// import RightPanel from '@/components/RightPanel'
import HeaderLink from './components/HeaderLink/index.vue'
// import RightCard from '@/components/RightCard/index.vue'
import ReceiptForm from '@/components/Module/ReceiptForm/index.vue'
import WeatherForm from '@/components/Module/WeatherForm/index.vue'
import LoginForm from '@/components/LoginForm'
import ItemMenu from './menu/ItemMenu.vue'
import Hamburger from '@/components/Hamburger'
import { AppMain } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import { mapState, mapGetters } from 'vuex'
import TemperWidget from './components/TemperWidget'
// import store from '../../store'

export default {
  name: 'Layout',
  components: {
    TemperWidget,
    AppMain,
    HeaderLink,
    // Navbar,
    ItemMenu,
    // RightPanel,
    WeatherForm,
    LoginForm,
    ReceiptForm,
    // RightCard,
    // Settings,
    // Sidebar,
    Hamburger
    // TagsView
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
      return this.device === 'mobile'
    },
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

  .hamburger-container {
    float: left;
    transition: background 1s;
    background-color: #79e5e5;
    -webkit-tap-highlight-color:transparent;
  }

</style>

