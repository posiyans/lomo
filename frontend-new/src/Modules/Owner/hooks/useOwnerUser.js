import { getOwnerUserInfo } from 'src/Modules/Owner/api/ownerUserApi'
import { ref } from 'vue'

export function useOwnerUser() {
  const owner = ref({})
  const steads = ref([])

  const getInfo = (uid, params) => {
    getOwnerUserInfo(uid, params)
      .then(res => {
        owner.value = res.data.owner
        steads.value = res.data.steads
      })
  }
  return {
    owner,
    getInfo,
    steads
  }
}
