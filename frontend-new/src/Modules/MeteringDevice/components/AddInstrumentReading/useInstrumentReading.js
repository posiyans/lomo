import { onMounted, ref } from 'vue'
import { date } from 'quasar'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'

export function useInstrumentReading() {
  const devices = ref({})
  const currentDate = ref(date.formatDate(new Date(), 'YYYY-MM-DD'))
  const allDevice = ref([])
  const loading = ref(false)
  const getData = (steadId) => {
    console.log('stead')
    console.log(steadId)
    loading.value = true
    getMeteringDeviceForStead(steadId)
      .then(res => {
        allDevice.value = res.data.data
        devices.value = {}
      })
      .finally(() => {
        loading.value = false
      })
  }
  const setValue = (val) => {
    console.log(val)
    if (val) {
      delete devices.value[val.device_id]
      if (val.value) {
        devices.value[val.device_id] = val
      }
    }
  }
  onMounted(() => {
    devices.value = {}
  })
  return {
    devices,
    currentDate,
    getData,
    allDevice,
    loading,
    setValue
  }
}
