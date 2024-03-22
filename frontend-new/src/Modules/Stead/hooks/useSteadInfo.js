import { ref } from 'vue'
import { getSteadInfo } from 'src/Modules/Stead/api/stead'
import { errorMessage } from 'src/utils/message'

export function useSteadInfo() {
  const stead = ref({})
  const getData = (steadId) => {
    const data = {
      id: steadId
    }
    getSteadInfo(data)
      .then(res => {
        stead.value = res.data.data
      })
      .catch(er => {
        errorMessage(er.response.data.errors)
      })
  }
  return {
    stead,
    getData
  }
}
