import { getOwnerFieldList } from 'src/Modules/Owner/api/ownerUserApi'
import { ref } from 'vue'

export function useOwnerUserField() {
  const fieldList = ref([])

  const getListField = () => {
    getOwnerFieldList()
      .then(res => {
        fieldList.value = res.data.data
      })
  }
  return {
    fieldList,
    getListField
  }
}
