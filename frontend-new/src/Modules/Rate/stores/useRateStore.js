import { defineStore } from 'pinia'
import { getRateGroupList } from 'src/Modules/Rate/api/rateAdminApi'

export const useRateStore = defineStore('rateStore', {
  state: () => ({
    load: false,
    rateGroupList: [],
    loading: false
  }),
  getters: {
    rateGroupInfo: (state) => (rateGroupId) => {
      const tmp = state.rateGroupList.find(item => item.id === rateGroupId)
      if (tmp) {
        return tmp
      }
      return false
    }
  },
  actions: {
    getData() {
      if (!this.load) {
        this.loading = true
        getRateGroupList()
          .then(response => {
            this.rateGroupList = response.data.data
            this.loading = false
            this.load = true
          })
      }
    }
  }
})
