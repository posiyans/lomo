import { login, logout, getInfo, loginVK, userRegister } from '@/api/user/user.js'
import { getToken, setToken, removeToken } from '@/utils/auth'
import router, { resetRouter } from '@/router'
import store from '@/store'
import { Message } from 'element-ui'

const state = {
  token: getToken(),
  name: '',
  avatar: '',
  introduction: '',
  roles: [],
  info: {}
}

const mutations = {
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_INTRODUCTION: (state, introduction) => {
    state.introduction = introduction
  },
  SET_NAME: (state, name) => {
    state.name = name
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar
  },
  SET_ROLES: (state, roles) => {
    state.roles = roles
  },
  SET_INFO: (state, info) => {
    state.info = info
  }
}

const actions = {
  // user login
  register({ commit }, userData) {
    const { name, email, password, password_confirmation } = userData
    return new Promise((resolve, reject) => {
      userRegister({ name: name.trim(), email: email.trim(), password: password.trim(), password_confirmation: password_confirmation.trim() }).then(response => {
        store.dispatch('user/getInfo')
        // const { data } = response
        // commit('SET_TOKEN', data.token)
        // setToken(data.token)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },
  login({ commit }, userInfo) {
    const { email, password, remember } = userInfo
    return new Promise((resolve, reject) => {
      login({ email: email.trim(), password: password.trim(), remember: remember }).then(response => {
        console.log(response)
        if (response.status === 422) {
          Message({
            message: 'Неверный пароль или E-mail!',
            type: 'error',
            duration: 15 * 1000
          })
        }
        store.dispatch('user/getInfo')
        // const { data } = response
        // commit('SET_TOKEN', data.token)
        // setToken(data.token)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },
  loginVK({ commit }) {
    return new Promise((resolve, reject) => {
      loginVK({}).then(response => {
        const { data } = response
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  },
  // get user info
  getInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      getInfo(state.token).then(response => {
        const { data } = response
        if (!data) {
          reject('Verification failed, please Login again.')
        }

        const { roles, name, avatar, introduction, allPermissions } = data

        // roles must be a non-empty array
        if (!allPermissions || allPermissions.length <= 0) {
          reject('getInfo: roles must be a non-null array!')
        }
        if (data.message) {
          Message({
            message: data.message,
            type: 'warning',
            duration: 5 * 1000
          })
        }
        commit('SET_INFO', data)
        commit('SET_ROLES', allPermissions)
        commit('SET_NAME', name)
        commit('SET_AVATAR', avatar)
        commit('SET_INTRODUCTION', introduction)
        resolve(data)
      })
      // }).catch(error => {
      //   reject(error)
      // })
    })
  },

  // user logout
  logout({ commit, state, dispatch }) {
    return new Promise((resolve, reject) => {
      logout(state.token).then(() => {
        commit('SET_TOKEN', '')
        commit('SET_ROLES', [])
        removeToken()
        resetRouter()
        // getInfo()
        // dispatch('permission/getMenu')
        // reset visited views and cached views
        // to fixed https://github.com/PanJiaChen/vue-element-admin/issues/2485
        dispatch('tagsView/delAllViews', null, { root: true })
        // await
        // await dispatch('permission/getMenu')
        resolve()
      }).catch(error => {
        reject(error)
      })
    }).then(() => {
      dispatch('getInfo')
    }).then(() => {
      store.dispatch('permission/getMenu')
    })
  },

  // remove token
  resetToken({ commit }) {
    return new Promise(resolve => {
      commit('SET_TOKEN', '')
      commit('SET_ROLES', [])
      removeToken()
      resolve()
    })
  },

  // dynamically modify permissions
  changeRoles({ commit, dispatch }, role) {
    return new Promise(async resolve => {
      const token = role + '-token'

      commit('SET_TOKEN', token)
      setToken(token)

      const { roles } = await dispatch('getInfo')

      resetRouter()

      // generate accessible routes map based on roles
      const accessRoutes = await dispatch('permission/generateRoutes', roles, { root: true })

      // dynamically add accessible routes
      router.addRoutes(accessRoutes)

      // reset visited views and cached views
      dispatch('tagsView/delAllViews', null, { root: true })

      resolve()
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
