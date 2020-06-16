<template>
  <div>
    <el-tabs type="border-card">
      <slot></slot>
      <el-tab-pane label="Роли">
        <el-checkbox-group
          v-model="value.roles"
          @change="chageData"
          >
          <div v-for="role in roles" :key="role.name">
            <el-checkbox :label="role.name" :disabled="role.name | roleFilter(my_user)">{{role.display_name}}</el-checkbox>

          </div>
        </el-checkbox-group>
      </el-tab-pane>
      <el-tab-pane label="Разрешения">
        <el-checkbox-group
          v-model="value.permissions"
          @change="chageData"
        >
          <div v-for="permission in permissions" :key="permission.name">
            <el-checkbox :label="permission.name" :disabled="permission.name | permissionFilter(my_user)">{{permission.display_name}}</el-checkbox>
          </div>

        </el-checkbox-group>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import { fetchList } from '@/api/admin/role.js'
export default {
  props: {
    value: {
      type: Object,
      default: {}
    }
  },
  filters:{
    roleFilter(val, user){
      const index = user.roles.findIndex(v => v.name === 'superAdmin')
      // return true
      if (user.roles.findIndex(v => v.name === 'superAdmin') != -1){
        return false
      }
      if (user.roles.findIndex(v => v.name === val) != -1){
        return false
      }
      return true
    },
    permissionFilter(val, user){
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
    chageData(){
      this.$emit('input', this.value)
    },
    fetchRoles(){
      fetchList()
       .then(response =>{
         this.roles = response.data.roles
         this.permissions = response.data.permissions
       })
    }
  }

}
</script>

<style scoped>

</style>
