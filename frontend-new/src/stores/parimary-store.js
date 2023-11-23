import { defineStore } from 'pinia'

export const usePrimaryStore = defineStore('primary', {
  state: () => ({
    pageName: 'Панель управления'
  }),
  // getters: {
  //   doubleCount: (state) => state.counter * 2
  // },
  actions: {
    setPageName(val) {
      this.pageName = val
    }
  }
})
