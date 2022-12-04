<template>
  <div>
    <q-btn v-if="admin" no-caps no-wrap stretch flat label="Админ панель" @click="showAdmin"/>
  </div>
  <div>
    <q-btn v-if="admin" no-caps no-wrap stretch flat label="Админ панель2" to="/new-admin"/>
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue'

import { useUserStore } from 'src/Modules/User/store/user-store'

export default defineComponent({
  setup () {
    const userStore = useUserStore()
    const admin = computed(() => {
      return userStore.permissions.includes('access-admin-panel')
    })
    const showAdmin = () => {
      window.location = '/admin'
    }
    return {
      admin,
      showAdmin
    }
  }
})

</script>
