<template>
  <div>
    <el-menu-item v-if="!item.children" :index="item.basePath">{{ item.label }}</el-menu-item>
    <el-submenu v-else :index="item.basePath">
      <template v-slot:title>{{ item.label }}</template>
      <sidebar-item
        v-for="route in item.children"
        :key="route.id"
        :item="route"
      />
    </el-submenu>
  </div>
</template>

<script>
import FixiOSBug from './FixiOSBug'

export default {
  name: 'SidebarItem',
  mixins: [FixiOSBug],
  props: {
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
  data () {
    return {}
  },
  computed: {
    mobile () {
      return this.$store.state.app.device === 'mobile'
    }
  },
  mounted () {

  },
  methods: {

  }
}
</script>
<style>
.el-submenu__icon-arrow {
  right: 5px;
}
.el-submenu__title i {
  color: #fff;
}
.el-submenu__title {
  padding-right: 25px;
}
</style>
