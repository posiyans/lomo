import { defineStore } from 'pinia'
import { getAuthPathVK, getInfo, userLoginByEmail, userLogout, userRegister } from 'src/Modules/Auth/api/auth'
import { Notify } from 'quasar'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenu'

const menuStore = useSiteMenuStore()
export const useUserStore = defineStore('user', {
  state: () => ({
    loading: true,
    is_guest: true,
    user: {},
    permissions: []
  }),
  getters: {},
  actions: {
    getMyinfo() {
      menuStore.getMenu()
      return new Promise(resolve => {
        this.loading = true
        getInfo()
          .then(res => {
            this.user = res.data.user
            this.permissions = res.data.permissions
            this.is_guest = false
            resolve(this.permissions)
            // menuStore.generateAdminMenu(this.permissions)
          })
          .catch(() => {
            resolve({})
          })
          .finally(() => {
            this.loading = false
          })
      })
    },
    login(data) {
      userLoginByEmail(data)
        .then(res => {
          this.getMyinfo()
        })
        .catch(error => {
          console.log(error.response.status)
          if (error.response.status) {
            Notify.create({
              message: 'Неверный E-mail или пароль',
              color: 'negative'
            })
          }
          this.user = ''
          this.permissions = []
          this.is_guest = true
        })
    },
    logout() {
      userLogout()
        .then(res => {
          this.user = ''
          this.permissions = []
          this.is_guest = true
          this.getMyinfo()
        })
    },
    register(data) {
      return new Promise((resolve, reject) => {
        userRegister(data)
          .then(() => {
            this.getMyinfo()
            resolve()
          })
          .catch(er => {
            reject(er)
          })
      })
    },
    getAuthVkPath() {
      return new Promise((resolve, reject) => {
        getAuthPathVK().then(response => {
          const { data } = response
          resolve(data)
        }).catch(error => {
          reject(error)
        })
      })
    }
  }
})
