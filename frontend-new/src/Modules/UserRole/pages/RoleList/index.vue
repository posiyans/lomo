<template>
  <div class="q-pa-md">
    <el-table :data="rolesList" style="width: 100%;margin-top:30px;" border>
      <el-table-column align="center" label="Role Key" width="220">
        <template #default="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="Название" width="300">
        <template #default="scope">
          {{ scope.row.display_name }}
        </template>
      </el-table-column>
      <el-table-column align="header-center" label="Описание">
        <template #default="scope">
          {{ scope.row.description }}
        </template>
      </el-table-column>
      <el-table-column align="header-center" label="Права">
        <template #default="scope">
          <span v-for="(item, i) in scope.row.permissions" :key="i.name" style="font-size: 0.9em;"><span v-if="i > 0">, </span>{{ item.display_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Действия">
        <template #default="scope">
          <el-button type="primary" size="small" @click="handleEdit(scope)">Edit</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog v-model="dialogVisible" title="Редактирование">
      <el-form :model="role" label-width="80px" label-position="left">
        <el-form-item label="Название">
          <el-input v-model="role.display_name" placeholder="Название роли" />
        </el-form-item>
        <el-form-item label="Описание">
          <el-input
            v-model="role.description"
            :autosize="{ minRows: 2, maxRows: 4}"
            type="textarea"
            placeholder="Описание роли"
          />
        </el-form-item>
        <el-form-item label="Права">
          <el-tree
            ref="tree"
            :check-strictly="checkStrictly"
            :data="routesData"
            node-key="name"
            :props="defaultProps"
            show-checkbox
            class="permission-tree"
          />
        </el-form-item>
      </el-form>
      <div style="text-align:right;">
        <el-button type="danger" @click="dialogVisible=false">Cancel</el-button>
        <el-button type="primary" @click="confirmRole">Confirm</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { getRoles, updateRole } from 'src/Modules/UserRole/api/role-api.js'

const defaultRole = {
  key: '',
  name: '',
  description: '',
  routes: []
}

export default {
  data() {
    return {
      role: Object.assign({}, defaultRole),
      routes: [],
      rolesList: [],
      dialogVisible: false,
      checkStrictly: false,
      defaultProps: {
        children: 'children',
        label: 'display_name'
      }
    }
  },
  computed: {
    routesData() {
      return this.routes
    }
  },
  mounted() {
    this.getRoles()
  },
  methods: {
    async getRoles() {
      const res = await getRoles()
      this.rolesList = res.data.data.roles
      this.routes = res.data.data.permissions
    },
    handleEdit(scope) {
      this.dialogVisible = true
      this.checkStrictly = true
      this.role = Object.assign({}, scope.row)
      this.$nextTick(() => {
        const val = scope.row.permissions.map(item => {
          return item.name
        })
        this.$refs.tree.setCheckedKeys(val)
        // set checked state of a node not affects its father and child nodes
        this.checkStrictly = false
      })
    },
    async confirmRole() {
      const checkedKeys = this.$refs.tree.getCheckedKeys()
      this.role.newPermissions = checkedKeys

      await updateRole(this.role.id, this.role)
      this.getRoles()

      // console.log(this.rolesList);
      // const { description, display_name, name } = this.role
      this.dialogVisible = false
      this.$notify({
        title: 'Сохранено',
        dangerouslyUseHTMLString: true,
        message: 'ok',
        type: 'success'
      })
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
