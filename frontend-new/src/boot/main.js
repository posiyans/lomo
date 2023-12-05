import { boot } from 'quasar/wrappers'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenu'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import { usePrimaryStore } from 'stores/parimary-store'

const authStore = useAuthStore()
const siteMenuStore = useSiteMenuStore()
const primaryStore = usePrimaryStore()
const title = 'СНТ'

export default boot(async ({ router }) => {
  const permission = await authStore.getMyInfo()
  siteMenuStore.getSiteMenu()
  const route = await siteMenuStore.generateRoutes(permission)
  route.forEach(item => {
    router.addRoute(item)
  })
  router.beforeEach(async (to, from, next) => {
    document.title = getPageTitle(to.meta.title)
    primaryStore.setPageName(to.meta.title || 'Панель управления')
    if (to.meta.guest) {
      if (authStore.is_guest === to.meta.guest) {
        next()
      } else {
        next('/')
      }
      return true
    } else {
      if (to.meta?.roles && to.meta.roles.length > 0) {
        if (authStore.permissions.filter(x => to.meta.roles.includes(x)).length > 0) {
          next()
        } else {
          next('/')
        }
        return true
      }
      next()
    }
  })
})

function getPageTitle(pageTitle) {
  if (pageTitle) {
    return `${pageTitle} - ${title}`
  }
  return `${title}`
}
