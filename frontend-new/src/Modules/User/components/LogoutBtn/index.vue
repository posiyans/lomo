<template>
  <div @click="logout" >
    <q-item clickable v-close-popup >
      <q-item-section>
        <q-item-label>Выйти</q-item-label>
      </q-item-section>
    </q-item>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { useUserStore } from 'src/Modules/User/store/user-store.js'
import { useQuasar } from 'quasar'

export default defineComponent({
  setup () {
    const userStore = useUserStore()
    const $q = useQuasar()

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
        userStore.logout()
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
