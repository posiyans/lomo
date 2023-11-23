import { defineStore } from 'pinia'
import { fetchList } from 'src/Modules/Users/api/role-admin-api'

export const useRoleAndPermissionStore = defineStore('roleAndPermission', {
  state: () => ({
    roles: [],
    permissions: [],
    load: false
  }),
  getters: {},
  actions: {
    getRolesAndPermissions() {
      fetchList()
        .then(res => {
          this.roles = res.data.roles
          this.permissions = res.data.permissions
          console.log(this.roles)
        })
    }
  }
})
