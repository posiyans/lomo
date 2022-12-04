<template>
  <div>
    <el-menu-item v-if="!item.children" :index="item.basePath">{{ item.label }}</el-menu-item>
    <el-sub-menu v-else :index="item.basePath">
      <template #title>{{ item.label }}</template>
      <sidebar-item
          v-for="route in item.children"
          :key="route.id"
          :item="route"
      />
    </el-sub-menu>
  </div>
</template>

<script>

export default {
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
  }
}
</script>
<style>

</style>
