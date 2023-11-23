<template>
  <div>
    <el-tabs type="border-card">
      <slot></slot>
      <el-tab-pane label="Роли">
        <el-checkbox-group
          :model-value="[]"
          @update:model-value="chageData"
        >
          <div v-for="role in roles" :key="role.name">
            <el-checkbox :label="role.name" :disabled="roleFilter(role.name, my_user)">{{ role.display_name }}</el-checkbox>
          </div>
        </el-checkbox-group>
      </el-tab-pane>
      <el-tab-pane label="Разрешения">
        <el-checkbox-group
          :model-value="[]"
          @change="chageData"
        >
          <div v-for="permission in permissions" :key="permission.name">
            <el-checkbox :label="permission.name" :disabled="permissionFilter(permission.name, my_user)">{{ permission.display_name }}</el-checkbox>
          </div>

        </el-checkbox-group>
      </el-tab-pane>
      <el-tab-pane label="Действия">
        <div v-if="!userRole.email_verified_at">
          Повторное потверждение почты
          <el-button type="primary" size="small" @click="sentToken">
            Отправить
          </el-button>
        </div>

      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import { fetchList } from 'src/Modules/Users/api/role-admin-api.js'
import { sendVerifyMailToken } from 'src/Modules/Users/api/user-admin-api.js'

export default {
  props: {
    modelValue: {
      type: Object,
      default: () => {
      }
    }
  },
  data() {
    return {
      roles: {},
      userRole: {},
      permissions: {}
    }
  },
  computed: {
    my_user() {
      return 'this.$store.state.user.info'
    }
  },
  mounted() {
    this.userRole = this.modelValue
    this.fetchRoles()
  },
  methods: {
    roleFilter(val, user) {
      // const index = user.roles.findIndex(v => v.name === 'superAdmin')
      // return true
      if (user.roles.findIndex(v => v.name === 'superAdmin') !== -1) {
        return false
      }
      if (user.roles.findIndex(v => v.name === val) !== -1) {
        return false
      }
      return true
    },
    permissionFilter(val, user) {
      if (user.roles.findIndex(v => v.name === 'superAdmin') !== -1) {
        return false
      }
      if (user.permissions.findIndex(v => v.name === val) !== -1) {
        return false
      }
      return true
    },
    sentToken() {
      sendVerifyMailToken(this.modelValue.id).then(response => {
        if (response.data.status) {
          this.$message({
            message: response.data.data,
            type: 'success'
          })
        } else {
          this.$message({
            message: response.data.data,
            type: 'error'
          })
        }
      })
    },
    chageData() {
      this.$emit('input', this.userRole)
    },
    fetchRoles() {
      fetchList().then(response => {
        this.roles = response.data.roles
        this.permissions = response.data.permissions
      })
    }
  }

}
</script>

<style scoped>

</style>
