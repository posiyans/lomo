<template>
  <component :is="block">
    {{ title }}
  </component>
</template>

<script>
import { fileSize } from '@/utils/filters.js'
export default {
  props: {
    size: {
      type: [Number, String],
      required: true
    },
    block: {
      type: String,
      default: 'div'
    }
  },
  computed: {
    title() {
      if (this.size) {
        return fileSize(this.size)
      }
      return ''
    }
  }
}
</script>

<style scoped>

</style>
