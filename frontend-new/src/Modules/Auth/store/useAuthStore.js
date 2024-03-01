import { defineStore } from 'pinia'
import { getAuthPathVK, getCsrfCookieToken, getInfo, userLoginByEmail, userLogout, userRegister } from 'src/Modules/Auth/api/auth'
import { errorMessage } from 'src/utils/message'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    loading: true,
    loginLoading: false,
    registerLoading: false,
    is_guest: true,
    user: {},
    permissions: [],
    roles: [],
    key: 1
  }),
  getters: {
    isOwner: (state) => {
      return state.roles.includes('owner')
    },
    checkPermission: (state) => {
      return (role) => {
        if (Array.isArray(role)) {
          let tmp = false
          role.forEach(item => {
            if (state.permissions.includes(item)) {
              tmp = true
            }
          })
          return tmp
        } else {
          return state.permissions.includes(role)
        }
      }
    }
  },
  actions: {
    getMyInfo(refresh = false) {
      return new Promise(resolve => {
        if (!refresh) {
          this.loading = true
        }
        getInfo()
          .then(res => {
            this.user = res.data.user
            this.permissions = res.data.permissions
            this.roles = res.data.roles
            this.is_guest = false
            resolve(res.data)
          })
          .catch(() => {
            this.user = {}
            this.permissions = []
            this.is_guest = true
            resolve({})
          })
          .finally(() => {
            this.loading = false
          })
      })
    },
    register(data) {
      return new Promise((resolve, reject) => {
        this.registerLoading = true
        getCsrfCookieToken()
          .then(res => {
            userRegister(data)
              .then(() => {
                resolve()
              })
              .catch(er => {
                reject(er)
              })
              .finally(() => {
                this.registerLoading = false
              })
          })
      })
    },
    logout() {
      return new Promise((resolve, reject) => {
        userLogout()
          .then(res => {
            this.user = {}
            this.permissions = []
            this.is_guest = true
            resolve()
          })
      })
    },
    login(data) {
      return new Promise((resolve, reject) => {
        this.loginLoading = true
        getCsrfCookieToken()
          .then(res => {
            userLoginByEmail(data)
              .then(res => {
                const data = res.data
                console.log('res')
                console.log(res)
                if (res.data.status === 'done') {
                  this.user = res.data.data.user
                  this.permissions = res.data.data.permissions
                  this.roles = res.data.data.roles
                  this.is_guest = false
                  resolve(data)
                } else {
                  resolve(data)
                }
              })
              .catch(error => {
                errorMessage(error.response.data.errors)
                this.user = {}
                this.permissions = []
                this.is_guest = true
                reject(error)
              })
              .finally(() => {
                this.loginLoading = false
              })
          })
      })
    },
    getAuthVkPath() {
      return new Promise((resolve, reject) => {
        getAuthPathVK()
          .then(response => {
            const { data } = response
            resolve(data)
          })
          .catch(error => {
            reject(error)
          })
      })
    }
  }
})
