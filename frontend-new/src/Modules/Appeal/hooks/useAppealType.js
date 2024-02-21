import { onMounted, ref } from 'vue'
import { fetchAppealType } from 'src/Modules/Appeal/api/appealTypeApi'

export function useAppealType() {
  const type = ref([])
  const getType = () => {
    fetchAppealType()
      .then(res => {
        type.value = res.data.data
      })
  }
  onMounted(() => {
    getType()
  })
  return {
    type,
    getType
  }
}
