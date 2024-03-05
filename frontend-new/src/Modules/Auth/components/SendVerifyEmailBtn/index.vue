<template>
  <div @click="sendCheckEmail">
    <slot>
      <q-btn icon="announcement" :loading="loading" flat color="negative">
        <q-tooltip>
          Email не подтвержден, Нажмите чтоб отправитть письмо с кодом подтверждения
        </q-tooltip>
      </q-btn>
    </slot>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { sendVerifyEmail } from 'src/Modules/Auth/api/auth'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {},
  props: {
    userUid: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const loading = ref(false)
    const $q = useQuasar()
    const sendCheckEmail = () => {
      loading.value = true
      sendVerifyEmail({ uid: props.userUid })
        .then(() => {
          emit('success')
        })
        .catch(er => {
          if (er.response.status === 429) {
            $q.dialog({
              color: 'negative',
              title: 'Превышен лимит',
              message: 'Запросы можно посылать раз в 24 часа'
            })
          }
        })
        .finally(() => {
          loading.value = false
        })
    }
    onMounted(() => {

    })
    return {
      sendCheckEmail,
      loading
    }
  }
})
</script>

<style scoped>

</style>
