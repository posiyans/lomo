<template>
  <el-menu-item v-if="!item.children" :index="item.id">{{ item.label }}</el-menu-item>
  <el-submenu v-else :index="item.id">
    <template slot="title">{{ item.label }}</template>
    <div v-for="i in item.children" :key="i">
      <Item :item="i" />
    </div>
  </el-submenu>
</template>

<script>
import Item from './Item.vue'
export default {
  name: 'Item',
  components: {
    Item
  },
  props: {
    item: {
      type: Object,
      default: () => ({})
    }
  }
}
</script>
