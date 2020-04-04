<template>
  <el-menu
    :default-active="activeIndex"
    class="el-menu-demo"
    mode="horizontal"
    @select="handleSelect"
    background-color="#545c64"
    text-color="#fff"
    active-text-color="#ffd04b">
    <sidebar-item v-for="route in menul" :item="route"/>
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
    menul() {
      return this.$store.state.permission.menu
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
    console.log('menu item menu')
    console.log(this.$store.state.permission.menu)
    console.log(this.menul)
  },
  methods: {
    handleSelect(key, keyPath) {

      console.log(this.$router)
      this.$router.push(key)
      console.log(key, keyPath)
    },
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    }
  }

}
</script>
