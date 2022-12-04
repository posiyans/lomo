/* eslint-disable */
<template>
  <q-list padding>
    <q-item clickable v-ripple to="/new-admin">
      <q-item-section avatar>
        <q-icon name="dashboard" />
      </q-item-section>

      <q-item-section>
        Главная
      </q-item-section>
    </q-item>

    <q-item clickable v-ripple to="/">
      <q-item-section avatar>
        <q-icon name="pageview" />
      </q-item-section>

      <q-item-section>
        Сайт
      </q-item-section>
    </q-item>
    <MenuItem v-for="item in routes" :key="item.path" :item="item" />
  </q-list>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref, computed, reactive } from 'vue'
import { useQuasar } from 'quasar'
import MenuItem from './MenuItem.vue'
import { asyncRoutes } from 'src/router/routes'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/menu-store'
const siteMenuStore = useSiteMenuStore()

export default defineComponent({
  components: {
    MenuItem
  },
  setup () {
    const $q = useQuasar()
    const routes = ref(siteMenuStore.adminMenu)
    return {
      routes
    }
  }
})
</script>

<style scoped>

</style>
