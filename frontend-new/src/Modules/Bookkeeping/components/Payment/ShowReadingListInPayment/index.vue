<template>
  <div>
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
            = {{ Math.floor(item.delta * item.rate.ratio_a) }} руб
          </div>
        </div>
        <q-space />
        <div class="text-center">
          <div class="text-weight-bold">
            {{ item.value }}
          </div>
          <div class="text-small-65">
            {{ item.previous_value }}
          </div>
        </div>
        <div v-if="edit">
          <DeleteInstrumentReading :reading-id="item.id" @success="reloadData" />
        </div>
      </div>
    </div>
    <div v-if="payment.readings && payment.readings.length === 0" class="text-red">
      Показания не найдены
    </div>
    <div v-else :class="className">
      <ShowPrice before-text="Итого: " :price="sumReadings" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowPrice from 'components/ShowPrice/index.vue'
import DeleteInstrumentReading from 'src/Modules/MeteringDevice/components/DeleteInstrumentReading/index.vue'

export default defineComponent({
  components: {
    ShowPrice,
    DeleteInstrumentReading
  },
  props: {
    payment: {
      type: Object,
      required: true
    },
    big: {
      type: Boolean,
      default: false
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const sumReadings = computed(() => {
      let sum = 0
      props.payment.readings.forEach(item => {
        sum += Math.floor(item.delta * item.rate.ratio_a)
      })
      return sum
    })
    const reloadData = () => {
      emit('reloadData')
    }
    const className = computed(() => {
      if (Math.floor(sumReadings.value) !== Math.floor(props.payment.price)) {
        return 'text-red text-weight-bold'
      }
      return ''
    })
    return {
      data,
      className,
      sumReadings,
      reloadData
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
