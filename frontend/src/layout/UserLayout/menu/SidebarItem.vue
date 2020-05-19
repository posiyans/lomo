<template>
    <el-menu-item v-if="!item.children" :index="item.basePath">{{item.label}}</el-menu-item>
    <el-submenu v-else :index="item.basePath">
      <template slot="title">{{item.label}}</template>
      <sidebar-item v-for="route in item.children" :item="route"/>
    </el-submenu>
</template>

<script>
import path from 'path'
import { isExternal } from '@/utils/validate'
import Item from './Item2'
import AppLink from './Link'
import FixiOSBug from './FixiOSBug'

export default {
  name: 'SidebarItem',
  components: { Item, AppLink },
  props: {
    // route object
    item: {
      type: Object,
      required: true
    },
    isNest: {
      type: Boolean,
      default: false
    },
    basePath: {
      type: String,
      default: ''
    }
  },
  data() {
    // To fix https://github.com/PanJiaChen/vue-admin-template/issues/237
    // TODO: refactor with render function
    this.onlyOneChild = null
    return {}
  },
  mounted() {
    // console.log('this.item')
    // console.log(this.item)
  },
  methods: {
    hasOneShowingChild(children = [], parent) {
      // const showingChildren = children.filter(item => {
      //   if (item.hidden) {
      //     return true
      //   } else {
      //     // Temp set(will be used if only has one showing child)
      //     this.onlyOneChild = item
      //     return true
      //   }
      // })

      // When there is only one child router, the child router is displayed by default
      // if (showingChildren.length === 1) {
      //   return true
      // }

      // Show parent if there are no child router to display
      if (children.length === 0) {
        this.onlyOneChild = parent
        console.log(this.onlyOneChild)
        return true
      }

      return false
    },
    resolvePath(routePath) {
      if (isExternal(routePath)) {
        return routePath
      }
      if (isExternal(this.basePath)) {
        return this.basePath
      }
      return 'path.resolve(this.basePath, routePath)'
    }
  }
}
</script>
