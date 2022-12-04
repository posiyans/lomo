import { boot } from 'quasar/wrappers'
import { useUserStore } from 'src/Modules/User/store/user-store'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/menu-store'
const userStore = useUserStore()
const siteMenuStore = useSiteMenuStore()

export default boot(async ({ router }) => {
  const permission = await userStore.getMyinfo()
  const route = await siteMenuStore.generateRoutes(permission)
  route.forEach(item => {
    router.addRoute(item)
  })
  router.beforeEach(async(to, from, next) => {
    next()
  })
})
