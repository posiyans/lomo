import { defineStore } from 'pinia'
import { getSiteMenu } from 'src/Modules/SiteMenu/api/menu'

export const useSiteMenuStore = defineStore('siteMenu', {
  state: () => ({
    loading: false,
    menu: [
      {
        label: 'Главная',
        basePath: '/',
        id: 1
      }
    ],
    adminMenu: []
  }),
  getters: {},
  actions: {
    getData() {
      this.loading = true
      getSiteMenu()
        .then(res => {
          this.menu = res.data
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
})
