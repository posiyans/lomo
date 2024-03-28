<template>
  <div class="row items-center no-wrap ">
    <div>
      <div>
        {{ device.rate.name }}
      </div>
      <div class="text-small-65">
        Sn: {{ device.serial_number }}
      </div>
    </div>
    <q-space />
    <div class="row">
      <div class="q-mr-xs">
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
            <div class="cursor-pointer hover-dark-blue">
              <q-icon name="post_add" />
              <q-popup-edit
                v-model.number="addValue"
                auto-save
                v-slot="scope"
                @update:model-value="addValueAction"
              >
                <q-input
                  v-model.number="scope.value"
                  dense
                  autofocus
                  label="Добавить к последнему значению"
                  @keyup.enter="scope.set"
                />
              </q-popup-edit>
            </div>
          </template>
        </q-input>
      </div>
      <div class="text-black" style="padding-top: 11px;">
        {{ device.rate.unit_name }}
      </div>
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
    const addValue = ref(0)
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
    const addValueAction = (val) => {
      const lastValue = +props.device.last_reading.value ?? 0
      reading.value.value = lastValue + +val
      setValue()
    }
    return {
      addValueAction,
      addValue,
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
