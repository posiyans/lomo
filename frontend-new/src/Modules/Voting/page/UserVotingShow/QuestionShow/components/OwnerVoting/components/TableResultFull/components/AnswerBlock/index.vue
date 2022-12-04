<template>
  <div :style="styleName">
    {{ label }}
  </div>
</template>

<script>
import { defineComponent, computed } from 'vue'
import ColorHash from 'color-hash'

export default defineComponent({
  props: {
    answer: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: [String, Number],
      default: ''
    }
  },
  setup (props) {
    const color = new ColorHash()
    const styleName = computed(() => {
      if (props.answer) {
        const saul = 'ab1cdaefg2td'
        const l = (props.answer + 123) * 1000
        return 'color: ' + color.hex(l + saul + props.answer + saul + props.answer + l + saul) + ';'
      }
      return 'color: #cecece;'
    })
    return {
      styleName
    }
  }
})
</script>

<style scoped>

</style>
