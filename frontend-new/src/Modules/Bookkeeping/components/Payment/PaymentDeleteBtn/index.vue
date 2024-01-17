<template>
  <div>
    <div @click="deleteAction">
      <slot>
        <q-btn label="Удалить" :loading="loading" color="negative" icon="delete" />
      </slot>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import { deletePayment } from 'src/Modules/Bookkeeping/api/paymentApi'
import { errorMessage } from 'src/utils/message'

export default defineComponent({
  components: {},
  props: {
    paymentId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const loading = ref(false)
    const $q = useQuasar()
    const deleteAction = () => {
      $q.dialog({
        title: 'Подтвердите',
        message: 'Подтвердите удаление платежа?',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'primary'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Удалить',
          color: 'negative'
        },
        persistent: true
      }).onOk(() => {
        loading.value = true
        deletePayment(props.paymentId)
          .then(() => {
            emit('success')
          })
          .catch(er => {
            errorMessage(er.response.data.errors)
          })
          .finally(() => {
            loading.value = false
          })

      })
    }
    return {
      loading,
      deleteAction
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

