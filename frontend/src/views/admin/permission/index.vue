<template>
  <div class="app-container">
    <el-table :data="rolesList" style="width: 100%;margin-top:30px;" border>
      <el-table-column align="center" label="Role Key" width="220">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column align="center" label="Название" width="300">
        <template slot-scope="scope">
          {{ scope.row.display_name}}
        </template>
      </el-table-column>
      <el-table-column align="header-center" label="Описание">
        <template slot-scope="scope">
          {{ scope.row.description }}
        </template>
      </el-table-column>
      <el-table-column align="header-center" label="Права">
        <template slot-scope="scope">
          <span v-for="(item, i) in scope.row.permissions" style="font-size: 0.9em;"><span v-if="i > 0">, </span>{{item.display_name}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Действия">
        <template slot-scope="scope">
          <el-button type="primary" size="small" @click="handleEdit(scope)">Edit</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :visible.sync="dialogVisible" title="Редактирование">
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
import path from 'path'
import { deepClone } from '@/utils'
import { getRoutes, getRoles, addRole, deleteRole, updateRole } from '@/api/role'

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
    // Mock: get all routes and roles list from server
    //this.getRoutes()
    this.getRoles()
  },
  methods: {
    async getRoutes() {
      const res = await getRoutes()
      this.serviceRoutes = res.data
      this.routes = this.generateRoutes(res.data)
    },
    async getRoles() {
      const res = await getRoles()
      this.rolesList = res.data.data.roles
      this.routes = res.data.data.permissions
    },

    // Reshape the routes structure so that it looks the same as the sidebar
    generateRoutes(routes, basePath = '/') {
      const res = []

      for (let route of routes) {
        // skip some route
        if (route.hidden) { continue }

        const onlyOneShowingChild = this.onlyOneShowingChild(route.children, route)

        if (route.children && onlyOneShowingChild && !route.alwaysShow) {
          route = onlyOneShowingChild
        }

        const data = {
          path: path.resolve(basePath, route.path),
          title: route.meta && route.meta.title

        }

        // recursive child routes
        if (route.children) {
          data.children = this.generateRoutes(route.children, data.path)
        }
        res.push(data)
      }
      return res
    },
    generateArr(routes) {
      let data = []
      routes.forEach(route => {
        data.push(route)
        if (route.children) {
          const temp = this.generateArr(route.children)
          if (temp.length > 0) {
            data = [...data, ...temp]
          }
        }
      })
      return data
    },
    // handleAddRole() {
    //   this.role = Object.assign({}, defaultRole)
    //   if (this.$refs.tree) {
    //     this.$refs.tree.setCheckedNodes([])
    //   }
    //   this.dialogVisible = true
    // },
    handleEdit(scope) {
      this.dialogVisible = true
      this.checkStrictly = true
      this.role = deepClone(scope.row)
      this.$nextTick(() => {
        const val = scope.row.permissions.map(item => {
          return item.name
        })
        this.$refs.tree.setCheckedKeys(val)
        // set checked state of a node not affects its father and child nodes
        this.checkStrictly = false
      })
    },
    // handleDelete({ $index, row }) {
    //   this.$confirm('Confirm to remove the role?', 'Warning', {
    //     confirmButtonText: 'Confirm',
    //     cancelButtonText: 'Cancel',
    //     type: 'warning'
    //   })
    //     .then(async() => {
    //       await deleteRole(row.key)
    //       this.rolesList.splice($index, 1)
    //       this.$message({
    //         type: 'success',
    //         message: 'Delete succed!'
    //       })
    //     })
    //     .catch(err => { console.error(err) })
    // },
    generateTree(routes, basePath = '/', checkedKeys) {
      const res = []

      for (const route of routes) {
        const routePath = path.resolve(basePath, route.path)

        // recursive child routes
        if (route.children) {
          route.children = this.generateTree(route.children, routePath, checkedKeys)
        }

        if (checkedKeys.includes(routePath) || (route.children && route.children.length >= 1)) {
          res.push(route)
        }
      }
      return res
    },
    async confirmRole() {
      // console.log(this.$refs.tree.getCheckedKeys())

      const checkedKeys = this.$refs.tree.getCheckedKeys()
      this.role.newPermissions = checkedKeys;
      // this.role.routes = this.generateTree(deepClone(this.serviceRoutes), '/', checkedKeys)


      await updateRole(this.role.id, this.role)
      this.getRoles()
        // for (let index = 0; index < this.rolesList.length; index++) {
        //   if (this.rolesList[index].id === this.role.id) {
        //     this.rolesList.splice(index, 1, Object.assign({}, this.role))
        //     break
        //   }
        // }


      // console.log(this.rolesList);
      const { description, display_name, name } = this.role
      this.dialogVisible = false
      this.$notify({
        title: 'Сохранено',
        dangerouslyUseHTMLString: true,
        message: `
            <div>Key: ${name}</div>
            <div>Название: ${display_name}</div>
            <div>Описание: ${description}</div>
          `,
        type: 'success'
      })
    },
    // reference: src/view/layout/components/Sidebar/SidebarItem.vue
    onlyOneShowingChild(children = [], parent) {
      let onlyOneChild = null
      const showingChildren = children.filter(item => !item.hidden)

      // When there is only one child route, the child route is displayed by default
      if (showingChildren.length === 1) {
        onlyOneChild = showingChildren[0]
        onlyOneChild.path = path.resolve(parent.path, onlyOneChild.path)
        return onlyOneChild
      }

      // Show parent if there are no child route to display
      if (showingChildren.length === 0) {
        onlyOneChild = { ... parent, path: '', noShowingChildren: true }
        return onlyOneChild
      }

      return false
    }
  }
}
</script>

<style lang="scss" scoped>
.app-container {
  .roles-table {
    margin-top: 30px;
  }
  .permission-tree {
    margin-bottom: 30px;
  }
}
</style>
