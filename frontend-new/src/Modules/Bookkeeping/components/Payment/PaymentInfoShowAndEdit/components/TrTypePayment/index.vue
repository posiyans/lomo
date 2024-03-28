<template>
  <tr :class="{'bg-red-1': typeError}">
    <td>Тип платежа</td>
    <td style="padding: 0 16px">
      <div v-if="editRateGroup" class="row items-center">
        <RateGroupSelect
          v-model="payment.rate_group_id"
          outlined
          dense
          class="filter-item"
        />
        <q-space />
        <div>
          <q-btn flat padding="sm" icon="save" color="green" @click="changeRateGroup" />
        </div>
      </div>
      <div v-else class="row items-center">
        <div>
          {{ payment.rate?.name }}
        </div>
        <q-space />
        <div v-if="edit">
          <q-btn icon="edit" flat color="primary" dense size="sm" @click="editRateGroup = !editRateGroup" />
        </div>
      </div>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'

export default defineComponent({
  components: {
    RateGroupSelect
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const { payment, updatePaymentData, getPaymentData } = useCurrentPayment()
    const editRateGroup = ref(false)
    const changeRateGroup = async () => {
      await updatePaymentData()
      getPaymentData()
      editRateGroup.value = false
    }
    const typeError = computed(() => {
      return !payment.value.rate
    })
    return {
      editRateGroup,
      typeError,
      changeRateGroup,
      payment
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
