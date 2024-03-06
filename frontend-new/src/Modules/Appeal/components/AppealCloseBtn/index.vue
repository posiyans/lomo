<template>
  <q-btn v-if="showBtn" rounded outline label="Закрыть обращение" color="negative" @click="closeAppealAction" />
  <q-btn v-else rounded outline label="Открытое обращение" color="negative" disable />
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import { closeAppeal } from 'src/Modules/Appeal/api/appealApi'
import { errorMessage } from 'src/utils/message'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export default defineComponent({
  components: {},
  props: {
    appeal: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const $q = useQuasar()
    const authStore = useAuthStore()
    const showBtn = computed(() => {
      return authStore.checkPermission(['appeal-edit']) || props.appeal.user?.uid === authStore.user?.uid
    })
    const closeAppealAction = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Закрыть данное обращение?',
        cancel: {
          label: 'Отмена',
          color: 'negative',
          flat: true
        },
        ok: {
          label: 'Закрыть'
        },
        persistent: true
      }).onOk(() => {
        closeAppeal(props.appeal.id)
          .then(res => {
            emit('success')
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
      })
    }
    return {
      closeAppealAction,
      showBtn
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
