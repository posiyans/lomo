<template>
  <el-menu
    :default-active="activeIndex"
    :ellipsis="false"
    class="el-menu-vertical-demo"
    :mode="vertical ? 'vertical' : 'horizontal'"
    background-color="#37474f"
    text-color="#fff"
    active-text-color="#ffd04b"
    @select="handleSelect"
  >
    <SidebarItem v-for="item in siteMenu.menu" :key="item.basePath" :item="item"  style="min-width: 150px;"/>
    <el-menu-item v-if="admin" @click="showAdmin">Админ панель</el-menu-item>
  </el-menu>
  <div v-if="siteMenu.loading" class="q-px-md">
    <q-spinner-dots
        color="white"
        size="2em"
    />
  </div>
</template>

<script>
import SidebarItem from './SidebarItem.vue'
import { defineComponent, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/menu-store.js'
import { useUserStore } from 'src/Modules/User/store/user-store'

export default defineComponent({
  components: {
    SidebarItem
  },
  props: {
    vertical: {
      type: Boolean,
      default: false
    }
  },
  setup () {
    const router = useRouter()
    const siteMenu = useSiteMenuStore()
    const activeIndex = ref('/')
    const handleSelect = (key, keyPath) => {
      router.push(key)
    }
    const userStore = useUserStore()
    const admin = computed(() => {
      return userStore.permissions.includes('access-admin-panel')
    })
    const showAdmin = () => {
      window.location = '/admin'
    }
    return {
      admin,
      showAdmin,
      activeIndex,
      siteMenu,
      handleSelect
    }
  }
})

</script>
