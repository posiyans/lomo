import { defineStore } from 'pinia'
import { fetchCategoryList } from 'src/Modules/Article/Category/api/category'

export const userCategoryStore = defineStore('articleCategory', {
  state: () => ({
    categoryList: [],
    load: false
  }),
  getters: {},
  actions: {
    getData() {
      if (!this.load) {
        this.load = true
        fetchCategoryList()
          .then(response => {
            this.categoryList = response.data
          })
      }
    }
  }
})
