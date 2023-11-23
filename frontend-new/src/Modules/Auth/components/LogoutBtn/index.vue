<template>
  <div @click="logout">
    <q-item clickable v-close-popup>
      <q-item-section>
        <q-item-label>Выйти</q-item-label>
      </q-item-section>
    </q-item>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenu'
import { useRouter } from 'vue-router'

export default defineComponent({
  setup() {
    const authStore = useAuthStore()
    const $q = useQuasar()
    const siteMenuStore = useSiteMenuStore()
    const router = useRouter()
    const logout = () => {
      $q.dialog({
        title: 'Выход',
        message: 'Вы точно хотите выйти из системы?',
        cancel: {
          noCaps: true,
          label: 'Отмена',
          color: 'negative',
          flat: true
        },
        ok: {
          noCaps: true,
          label: 'Выйти',
          color: 'primary'
        },
        persistent: true
      }).onOk(() => {
        authStore.logout()
          .finally(() => {
            siteMenuStore.getSiteMenu()
            router.push('/')
          })
      })
    }
    return {
      logout
    }
  }
})
</script>

<style scoped>

</style>
