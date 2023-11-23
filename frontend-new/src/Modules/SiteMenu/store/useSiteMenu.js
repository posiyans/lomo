import { defineStore } from 'pinia'
import { getSiteMenu } from 'src/Modules/SiteMenu/api/menu'
import { asyncRoutes } from 'src/router/routes'

function hasPermission(role, route) {
  if (route.meta && route.meta.roles) {
    return route.meta.roles.includes(role)
  } else {
    return true
  }
}

function filterAsyncRoutes(routes, role) {
  const res = []

  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(role, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, role)
      }
      res.push(tmp)
    }
  })

  return res
}

export const useSiteMenuStore = defineStore('siteMenuu', {
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
    getSiteMenu() {
      this.loading = true
      getSiteMenu()
        .then(res => {
          this.menu = res.data
        })
        .finally(() => {
          this.loading = false
        })
    },
    generateRoutes(role) {
      return new Promise(resolve => {
        const accessedRoutes = filterAsyncRoutes(asyncRoutes, role)
        this.adminMenu = accessedRoutes
        resolve(accessedRoutes)
      })
    }

  }
})
