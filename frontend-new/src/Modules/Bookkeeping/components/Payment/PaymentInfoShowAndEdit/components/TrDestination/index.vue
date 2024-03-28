<template>
  <tr>
    <td>Назначение</td>
    <td style="padding: 0px;">
      <q-expansion-item
        :label="payment.raw_data[4]"
      >
        <div class="bg-grey-2 q-pa-sm">
          <pre>{{ payment.raw_data }}           </pre>
        </div>
      </q-expansion-item>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const { payment } = useCurrentPayment()

    return {
      payment
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
