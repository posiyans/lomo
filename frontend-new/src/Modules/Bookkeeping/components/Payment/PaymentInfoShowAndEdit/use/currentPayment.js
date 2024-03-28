import { computed, ref } from 'vue'
import { getPayment, updatePayment } from 'src/Modules/Bookkeeping/api/paymentApi'

const payment = ref(null)
const paymentId = ref(null)
const loading = ref(false)
const firstLoading = ref(false)

export function useCurrentPayment() {
  const setPaymentId = (val) => {
    payment.value = null
    paymentId.value = val
    firstLoading.value = true
  }
  const sumReadings = computed(() => {
    let sum = 0
    if (payment.value?.readings) {
      payment.value.readings.forEach(item => {
        sum += item.cost
      })
    }
    return sum
  })
  const getPaymentData = () => {
    loading.value = true
    return new Promise((resolve, reject) => {
      getPayment(paymentId.value)
        .then(res => {
          payment.value = res.data.data
          resolve()
        })
        .catch(() => {
          reject()
        })
        .finally(() => {
          loading.value = false
          firstLoading.value = false
        })
    })
  }
  const updatePaymentData = () => {
    loading.value = true
    return new Promise((resolve, reject) => {
      updatePayment(paymentId.value, payment.value)
        .then(res => {
          resolve(res.data)
        })
        .catch(er => {
          reject(er.response)
        })
        .finally(() => {
          loading.value = false
        })
    })
  }
  return {
    payment,
    sumReadings,
    setPaymentId,
    getPaymentData,
    updatePaymentData,
    loading,
    firstLoading
  }
}
