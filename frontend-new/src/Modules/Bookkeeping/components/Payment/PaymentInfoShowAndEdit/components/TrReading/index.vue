<template>
  <tr v-if="payment.rate.depends === 2">
    <td>Показания</td>
    <td class="q-pt-sm">
      <div class="q-pr-md">
        <div v-if="editReading">
          <ReadingBlock
            v-for="item in devices"
            :key="item.id"
            :device="item"
            class="q-mb-xs"
          />
        </div>
        <div v-else>
          <div v-for="item in payment.readings" :key="item.id" class="q-mb-md">
            <div class="row items-center bg-blue-1 q-col-gutter-sm">
              <div class="q-pl-sm">
                <div>
                  {{ item.device.rate.name }}
                </div>
                <div class="text-small-65">
                  Sn: {{ item.device.serial_number }}
                </div>
              </div>
              <div class="text-small-65">
                <div>
                  {{ item.delta }} *
                  {{ item.rate.description }}
                </div>
                <ShowPrice :price="item.cost" before-text="= " />
              </div>
              <div class="text-weight-bold q-pr-sm">
                {{ item.value }}
              </div>
            </div>
          </div>
          <div v-if="payment.readings && payment.readings.length === 0" class="text-red">
            Показания не найдены
          </div>
          <div v-else>
            <ShowPrice before-text="Итого: " :price="toValue(sumReadings)" />
          </div>
        </div>
        <div v-if="edit" class="absolute-top-right">
          <q-btn icon="edit" flat color="primary" dense size="sm" @click="editReadingShow" />
        </div>
      </div>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref, toValue } from 'vue'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'
import ReadingBlock from './ReadingBlock.vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import { useCurrentPayment } from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/use/currentPayment'

export default defineComponent({
  components: {
    ReadingBlock,
    ShowPrice
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const editReading = ref(false)
    const { payment, getPaymentData, loading, updatePaymentData, sumReadings } = useCurrentPayment()

    const editReadingShow = () => {
      editReading.value = !editReading.value
      if (editReading.value) {
        getData()
      }
    }

    const devices = ref([])
    const getData = () => {
      getMeteringDeviceForStead(payment.value.stead_id)
        .then(res => {
          devices.value = res.data.data
        })
    }
    const reloadData = () => {
      getData()
      emit('reloadData')
    }
    return {
      sumReadings,
      reloadData,
      editReading,
      editReadingShow,
      devices,
      payment,
      toValue

    }
  }
})
</script>

<style scoped lang='scss'>

</style>
