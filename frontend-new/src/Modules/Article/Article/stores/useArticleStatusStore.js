import { defineStore } from 'pinia'
import { getStatusList } from 'src/Modules/Article/Article/api/article'

export const useArticleStatusStore = defineStore('articleStatusStore', {
  state: () => ({
    statusList: [],
    load: false
  }),
  getters: {},
  actions: {
    getData() {
      if (!this.load) {
        this.load = true
        getStatusList()
          .then(response => {
            this.statusList = response.data
          })
      }
    }
  }
})
