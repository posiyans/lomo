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
    <MenuItem v-for="item in adminRoutes" :key="item.path" :item="item" base-path="/admin" />
  </q-list>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import { useQuasar } from 'quasar'
import MenuItem from './MenuItem.vue'
import adminRoutes from 'src/router/adminRoutes.js'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'


export default defineComponent({
  components: {
    MenuItem
  },
  setup() {
    const $q = useQuasar()
    const authStore = useAuthStore()
    return {
      adminRoutes,
      authStore
    }
  }
})
</script>

<style scoped>

</style>
