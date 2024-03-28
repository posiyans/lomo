<template>
  <tr>
    <td>ФИО</td>
    <td>
      <div class="row items-center">
        <div>
          {{ payment.raw_data[3] }}
        </div>
        <q-space />
        <FindOwnerPopup
          v-if="edit"
          class="text-primary"
          :owner-name="payment.raw_data[3] || ''"
          @set-stead="setStead"
        />
      </div>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'
import FindOwnerPopup from 'src/Modules/Owner/components/FindOwnerPopup/index.vue'
import { Dialog } from 'quasar'

export default defineComponent({
  components: {
    FindOwnerPopup
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const { payment, getPaymentData, loading, updatePaymentData } = useCurrentPayment()
    const setStead = (val) => {
      console.log(val)
      Dialog.create({
        title: 'Изменить?',
        message: 'Перенести платеж на участок № ' + val.number + ' ?',
        cancel: true,
        persistent: true
      }).onOk(async () => {
        payment.value.stead_id = val.stead_id
        await updatePaymentData()
        getPaymentData()
      })
    }
    return {
      data,
      setStead,
      payment,
      loading
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
