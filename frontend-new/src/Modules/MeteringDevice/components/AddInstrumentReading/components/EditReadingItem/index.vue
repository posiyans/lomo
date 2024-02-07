<template>
  <div class="row items-center no-wrap justify-between">
    <div>
      <div>
        {{ device.rate.name }}
      </div>
      <div class="text-small-65">
        Sn: {{ device.serial_number }}
      </div>
    </div>
    <div>
      <q-input
        v-model="reading.value"
        dense
        outlined
        label="Показание"
        lazy-rules
        :error="errorValue"
        :error-message="hintText"
        no-error-icon
        :hint="hintText"
        @update:model-value="setValue"
      >
        <template v-slot:append>
          <div class="text-black text-small-65">
            {{ device.rate.unit_name }}
          </div>
        </template>
      </q-input>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    device: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const reading = ref({
      value: '',
      device_id: props.device.id,
    })
    const errorValue = computed(() => {
      if (reading.value.value) {
        return +reading.value.value < +props.device.last_reading.value
      }
      return false
    })
    let timer = null
    const hintText = computed(() => {
      return 'Последнее показание: ' + props.device.last_reading.value + ' ' + props.device.rate.unit_name
    })
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
        emit('setValue', reading.value)
      }, 500)
    }
    return {
      reading,
      hintText,
      setValue,
      errorValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
