<template>
  <div v-if="payment.error" @click="confirmData">
    <slot>
      <q-btn label="Подтвердить данные" icon="done" color="secondary" />
    </slot>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'
import { Dialog } from 'quasar'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const { payment, getPaymentData, loading, updatePaymentData } = useCurrentPayment()
    const confirmData = () => {
      Dialog.create({
        title: 'Подтвердите',
        message: 'Установить что что все данные в платеже проверены?',
        cancel: {
          noCaps: true,
          flat: true,
          label: 'Отмена',
          color: 'negative'
        },
        ok: {
          noCaps: true,
          outline: true,
          label: 'Сохранить',
          color: 'primary'
        },
        persistent: true
      }).onOk(async () => {
        payment.value.data_verified = true
        await updatePaymentData()
        getPaymentData()
      })
    }
    return {
      confirmData,
      payment,
      loading
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
