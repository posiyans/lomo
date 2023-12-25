import { defineStore } from 'pinia'
import { fetchUserInfo } from 'src/Modules/Users/api/user-admin-api'
import { fetchRoleForUser } from 'src/Modules/Users/api/role-admin-api'

export const useActiveUserStore = defineStore('activeUser', {
  state: () => ({
    key: 1,
    user: {},
    roles: [],
    permissions: ['guest'],
    userUid: '',
    load: false
  }),
  getters: {
    rolesArray: (state) => {
      return state.roles.map(item => {
        return item.name
      })
    },
    permissionsArray: (state) => {
      return state.permissions.map(item => {
        return item.name
      })
    },
    fullName() {
      let result = ''
      if (this.user.last_name) {
        result += this.user.last_name
      }
      if (this.user.name) {
        result += ' ' + this.user.name
      }
      if (this.user.middle_name) {
        result += ' ' + this.user.middle_name
      }
      return result
    }
  },
  actions: {
    init(uid) {
      this.userUid = uid
    },
    getUserInfo() {
      const data = {
        uid: this.userUid
      }
      fetchUserInfo(data)
        .then(response => {
          this.user = response.data.data
          this.key++
        })
    },
    getUserRole() {
      const data = {
        user_uid: this.userUid
      }
      fetchRoleForUser(data)
        .then(res => {
          this.roles = res.data.roles
          this.permissions = res.data.permissions
        })
    },
    changeRole(role) {

    }
    // updateUserInfo() {
    //   updateUserData(this.user, this.userUid)
    //     .then(response => {
    //       Notify.create({
    //         title: 'Успех',
    //         message: 'Данные успешно сохранены',
    //         color: 'secondary',
    //         timeout: 2000
    //       })
    //     })
    // }
  }
})
