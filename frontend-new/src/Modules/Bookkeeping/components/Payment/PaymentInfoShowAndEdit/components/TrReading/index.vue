<template>
  <tr v-if="payment.rate.depends === 2">
    <td>Показания</td>
    <td>
      <div class="row items-center">
        <div v-if="editReading">
          <ReadingBlock
            v-for="item in devices"
            :key="item.id" :device="item"
            :payment="payment"
            class="q-mb-xs"
          />
        </div>
        <div v-else class="col-grow">
          <div v-for="item in payment.readings" :key="item.id">
            <div class="row items-center bg-blue-1 q-mb-xs">
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
                <div>
                  = {{ item.delta * item.rate.ratio_a }} руб
                </div>
              </div>
              <q-space />
              <div class="text-weight-bold ">
                {{ item.value }}
              </div>
              <div v-if="edit">
                <DeleteInstrumentReading :reading-id="item.id" @success="reloadData" />
              </div>
            </div>
          </div>
          <div v-if="payment.readings && payment.readings.length === 0" class="text-red">
            Показания не найдены
          </div>
          <div v-else>
            <ShowPrice before-text="Итого: " :price="sumReadings" />
          </div>
        </div>
        <q-space />
        <div v-if="edit" class="absolute-top-right">
          <q-btn icon="edit" flat color="primary" dense size="sm" @click="editReadingShow" />
        </div>
      </div>
    </td>
  </tr>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'
import ReadingBlock from './ReadingBlock.vue'
import DeleteInstrumentReading from 'src/Modules/MeteringDevice/components/DeleteInstrumentReading/index.vue'
import ShowPrice from 'components/ShowPrice/index.vue'

export default defineComponent({
  components: {
    ReadingBlock,
    DeleteInstrumentReading,
    ShowPrice
  },
  props: {
    payment: {
      type: Object,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const editReading = ref(false)
    const sumReadings = computed(() => {
      let sum = 0
      props.payment.readings.forEach(item => {
        sum += item.delta * item.rate.ratio_a
      })
      return sum
    })
    let timer = null
    const editReadingShow = () => {
      editReading.value = !editReading.value
      if (editReading.value) {
        getData()
      } else {
        emit('reloadData')
      }
    }
    const data = ref(false)
    const devices = ref([])
    const getData = () => {
      getMeteringDeviceForStead(props.payment.stead_id)
        .then(res => {
          devices.value = res.data.data
        })
    }
    const setValue = () => {
      if (timer) clearTimeout(timer)
      timer = setTimeout(() => {
        if (reading.value.value) {
          const tmp = reading.value.value
          if (!isNaN(tmp)) {
            reading.value.value = tmp
          } else {
            reading.value.value = ''
          }
        }
      }, 500)
    }
    const reloadData = () => {
      emit('reloadData')
    }
    return {
      data,
      sumReadings,
      reloadData,
      editReading,
      editReadingShow,
      devices
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
