import { asyncRoutes } from 'src/router/routes'

/**
 * Используйте meta.role, чтобы определить, есть ли у текущего пользователя разрешение
 * @param roles
 * @param route
 */
function hasPermission(role, route) {
  if (route.meta && route.meta.roles) {
    return route.meta.roles.includes(role)
  } else {
    return true
  }
}

/**
 * Фильтрация таблиц асинхронной маршрутизации по рекурсии
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, role) {
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

export function generateRoutes({ commit }, role) {
  return new Promise(resolve => {
    const accessedRoutes = filterAsyncRoutes(asyncRoutes, role)
    commit('SET_ROUTES', accessedRoutes)
    resolve(accessedRoutes)
  })
}