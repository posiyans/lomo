<template>
  <div v-if="loading"
       class="text-center"
       style="min-height: 90px;"
  >
    <q-spinner
      color="primary"
      size="10em"
    />
  </div>
  <div
    v-else
    class="q-gutter-sm"
    style="min-height: 90px;"
  >
    <div
      v-for="item in allDevice"
      :key="item.id"
    >
      <EditReadingItem
        :device="item"
        @setValue="setValue"
      />
    </div>
  </div>
  <div v-if="showDateInput" class="q-pt-sm">
    <InputDate
      v-model="currentDate"
      outlined
      dense
      label="От какой даты"
      :rules="[required]"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent } from 'vue'
import MeteringDeviceForSteadSelect from 'src/Modules/MeteringDevice/components/MeteringDeviceForSteadSelect/index.vue'
import InputDate from 'components/Input/InputDate/index.vue'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'
import EditReadingItem from './components/EditReadingItem/index.vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { required } from 'src/utils/validators.js'

export default defineComponent({
  components: {
    MeteringDeviceForSteadSelect,
    InputDate,
    SteadSelect,
    EditReadingItem
  },
  props: {
    instrumentReading: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const devices = props.instrumentReading.devices
    const currentDate = props.instrumentReading.currentDate
    const allDevice = props.instrumentReading.allDevice
    const loading = props.instrumentReading.loading
    const setValue = props.instrumentReading.setValue

    const authStore = useAuthStore()
    const showDateInput = computed(() => {
      return authStore.checkPermission('reading-edit')
    })


    return {
      currentDate,
      showDateInput,
      devices,
      setValue,
      required,
      loading,
      allDevice,

    }
  }
})
</script>

<style scoped lang='scss'>

</style>
