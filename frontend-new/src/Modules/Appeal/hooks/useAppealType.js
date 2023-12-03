import { onMounted, ref } from 'vue'
import { fetchAppealType } from 'src/Modules/Appeal/api/appealTypeApi'

export function useAppealType() {
  const type = ref([])
  onMounted(() => {
    fetchAppealType()
      .then(res => {
        type.value = res.data.data
      })
  })
  return {
    type
  }
}
