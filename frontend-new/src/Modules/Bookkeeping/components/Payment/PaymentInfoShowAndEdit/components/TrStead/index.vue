<template>
  <tr :class="{'bg-red-1': steadError}">
    <td>Участок</td>
    <td>
      <div v-if="findSteadShow" class="row items-center">
        <SteadSelect
          v-model="payment.stead_id"
          outlined
          dense
          clearable
        />
        <q-space />
        <div>
          <q-btn flat padding="sm" icon="save" color="green" @click="changeStead" />
        </div>
      </div>
      <div v-else class="row items-center">
        <div>
              <span v-if="steadError">
                {{ payment.raw_data[2] }} -->
              </span>
          <q-chip v-if="payment.stead" outline square color="primary" text-color="white">
            {{ payment.stead?.number }}
          </q-chip>
          <span v-else>?</span>
        </div>
        <q-space />
        <div v-if="edit">
          <q-btn icon="edit" flat color="primary" dense size="sm" @click="findSteadShow = !findSteadShow" />
        </div>
      </div>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'

export default defineComponent({
  components: {
    SteadSelect
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const findSteadShow = ref(false)
    const { payment, getPaymentData, loading, updatePaymentData } = useCurrentPayment()
    const steadError = computed(() => {
      return payment.value.stead?.number !== payment.value?.raw_data[2]
    })
    const changeStead = async () => {
      await updatePaymentData()
      getPaymentData()
      findSteadShow.value = false
    }
    return {
      data,
      changeStead,
      findSteadShow,
      steadError,
      payment,
      loading
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
