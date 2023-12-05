/* eslint-disable */
<template>
  <q-list padding>
    <q-item clickable v-ripple to="/">
      <q-item-section avatar>
        <q-icon name="pageview" />
      </q-item-section>
      <q-item-section>
        Сайт
      </q-item-section>
    </q-item>
    <MenuItem v-for="(item, index) in filteredMenu" :key="index" :item="item" base-path="/admin" />
  </q-list>
</template>

<script>
import { computed, defineComponent } from 'vue'
import MenuItem from './MenuItem.vue'
import adminRoutes from 'src/router/adminRoutes.js'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {
    MenuItem
  },
  setup() {
    const filteredMenu = computed(() => {
      const data = []
      adminRoutes.forEach(item => {
        const tmp = show(item)
        if (tmp) {
          data.push(tmp)
        }
      })
      return data
    })
    const authStore = useAuthStore()
    const show = (item) => {
      if (item.hidden) {
        return false
      }
      if (!item.meta.title) {
        return false
      }
      let access = false
      if (item.meta?.roles) {
        authStore.permissions.forEach(role => {
          if (item.meta?.roles?.includes(role)) {
            access = true
          }
        })
      }
      if (access) {
        if (item.children) {
          const children = []
          item.children.forEach(child => {
            const tmp = show(child)
            if (tmp) {
              children.push(tmp)
            }
          })
          item.children = children
        }
        return item
      }
      return item
    }
    return {
      filteredMenu
    }
  }
})
</script>

<style scoped>

</style>
