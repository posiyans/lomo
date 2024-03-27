import { onMounted, ref } from 'vue'
import { date } from 'quasar'
import { getMeteringDeviceForStead } from 'src/Modules/MeteringDevice/api/meteringDeviceApi'
import { addInstrumentReading } from 'src/Modules/MeteringDevice/api/instrumentReadingApi'

export function useInstrumentReading() {
  const devices = ref({})
  const currentDate = ref(date.formatDate(new Date(), 'YYYY-MM-DD'))
  const allDevice = ref([])
  const loading = ref(false)
  const paymentId = ref(null)
  const getData = (steadId) => {
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
  const saveData = () => {
    return new Promise((resolve, reject) => {
      const data = {
        date: currentDate.value,
        devices: Object.values(devices.value),
        payment_id: paymentId.value
      }
      addInstrumentReading(data)
        .then(res => {
          resolve()
        })
        .catch(er => {
          reject(er.response.data.error)
        })
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
    setValue,
    saveData,
    paymentId
  }
}
