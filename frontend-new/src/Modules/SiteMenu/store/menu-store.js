import { defineStore } from 'pinia'
import { getMenu } from 'src/Modules/SiteMenu/api/menu'
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
  getters: {
  },
  actions: {
    getMenu() {
      this.loading = true
      getMenu({ children: true })
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
