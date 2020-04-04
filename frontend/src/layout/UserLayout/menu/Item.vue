<template>
    <el-menu-item v-if="!item.children" :index="item.id">{{item.label}}</el-menu-item>
    <el-submenu v-else :index="item.id">
      <template slot="title">{{item.label}}</template>
      <template v-for="i in item.children">
        <Item :item="i"/>
      </template>
    </el-submenu>
</template>

<script>
import Item from './Item.vue'
export default {
  name: 'Item',
  components: {
    Item,
  },
  props: {
    item: {
      type: Object
    },
  },

}
</script>
