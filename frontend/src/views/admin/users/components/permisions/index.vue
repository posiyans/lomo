<template>
  <div>
    <el-tabs type="border-card">
      <slot></slot>
      <el-tab-pane label="Роли">
        <el-checkbox-group
          v-model="value.roles.roles"
          @change="chageData"
          >
          <div v-for="role in roles" :key="role.name">
            <el-checkbox :label="role.name" :disabled="role.name | roleFilter(my_user)">{{role.display_name}}</el-checkbox>

          </div>
        </el-checkbox-group>
      </el-tab-pane>
      <el-tab-pane label="Разрешения">
        <el-checkbox-group
          v-model="value.roles.permissions"
          @change="chageData"
        >
          <div v-for="permission in permissions" :key="permission.name">
            <el-checkbox :label="permission.name" :disabled="permission.name | permissionFilter(my_user)">{{permission.display_name}}</el-checkbox>
          </div>

        </el-checkbox-group>
      </el-tab-pane>
      <el-tab-pane label="Действия">
        <div v-if="!value.email_verified_at">
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
import { fetchList } from '@/api/admin/role.js'
import { sendVerifyMailToken } from '@/api/admin/user.js'
export default {
  props: {
    value: {
      type: Object,
      default: {}
    }
  },
  filters: {
    roleFilter(val, user) {
      const index = user.roles.findIndex(v => v.name === 'superAdmin')
      // return true
      if (user.roles.findIndex(v => v.name === 'superAdmin') != -1) {
        return false
      }
      if (user.roles.findIndex(v => v.name === val) != -1) {
        return false
      }
      return true
    },
    permissionFilter(val, user) {
      if (user.roles.findIndex(v => v.name === 'superAdmin') != -1){
        return false
      }
      if (user.permissions.findIndex(v => v.name === val) != -1){
        return false
      }
      return true
    },
  },
  data() {
    return {
      roles: {},
      permissions: {}
    }
  },
  computed: {
    my_user() {
      return this.$store.state.user.info
    }
  },
  mounted() {
    this.fetchRoles()
  },
  methods: {
    sentToken() {
      sendVerifyMailToken(this.value.id).then(response => {
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
      this.$emit('input', this.value)
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
