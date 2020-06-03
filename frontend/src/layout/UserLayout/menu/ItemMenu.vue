<template>
  <el-menu
    :default-active="activeIndex"
    class="el-menu-vertical-demo"
    :mode="mode_menu"
    @select="handleSelect"
    background-color="#545c64"
    text-color="#fff"
    active-text-color="#ffd04b">
    <sidebar-item v-for="route in menul" :item="route" :key="route.basePath"/>
    <el-menu-item v-if="admin && screen_wight > 480" index="/admin-article/list" >Админ панель</el-menu-item>
  </el-menu>
</template>

<script>
import SidebarItem from './SidebarItem'
import { mapGetters } from 'vuex'

export default {
  name: 'MenuItem',
  components: {
    SidebarItem,
  },
  props: {
    menud: {
      type: Array
    },
  },
  data() {
    return {
      activeIndex: '1',
      form: {
        name: ''
      },
    }
  },
  computed: {
    ...mapGetters([
      'permission_routes',
      'sidebar'
    ]),
    screen_wight(){
      return document.documentElement.clientWidth
    },
    mode_menu(){
      if(this.screen_wight < 500){
        return 'vertical'
      }
      return 'horizontal'
    },
    menul() {
      return this.$store.state.permission.menu
    },
    admin() {
      if (this.$store.getters.user.allPermissions.includes('access-admin-panel')) {
        return true
      }
      return false
    },
    activeMenu() {
      const route = this.$route
      const { meta, path } = route
      // if set path, the sidebar will highlight the path you set
      if (meta.activeMenu) {
        return meta.activeMenu
      }
      return path
    },
    showLogo() {
      return this.$store.state.settings.sidebarLogo
    },
    isCollapse() {
      return !this.sidebar.opened
    }
  },
  mounted() {
    // console.log('menu item menu')
    // console.log(this.$store.state.permission.menu)
    // console.log(document.documentElement.clientWidth)
  },
  methods: {
    handleSelect(key, keyPath) {
      this.$router.push(key)
      this.$emit('toggleClick')
    },
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    }
  }

}
</script>
