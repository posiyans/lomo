<template>
  <ItemMenu v-for="route in modelValue" :key="route.id" :item="route" :first="first" :icon="icon" />
</template>

<script>
import { defineComponent } from 'vue'
import ItemMenu from './ItemMenu.vue'

export default defineComponent({
  components: {
    ItemMenu
  },
  props: {
    modelValue: {
      type: Array,
      default: () => []
    },
    icon: {
      type: Boolean,
      default: false
    },
    first: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    return {}
  }
})
</script>

<style scoped>

</style>
