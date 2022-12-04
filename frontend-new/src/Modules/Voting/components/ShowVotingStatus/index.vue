<template>
  <span :class="className">
    {{name}}
  </span>
</template>

<script>
import { defineComponent, computed } from 'vue'
import { votingStatus } from 'src/Modules/Voting/constant.js'

export default defineComponent({
  props: {
    status: {
      type: String,
      required: true
    },
    color: {
      type: Boolean,
      default: false
    }
  },
  setup (props) {
    const obj = computed(() => {
      return votingStatus.find(item => {
        return item.value === props.status
      })
    })
    const name = computed(() => {
      if (obj.value) {
        return obj.value.label
      }
      return props.status
    })
    const className = computed(() => {
      if (props.color && obj) {
        return 'text-' + obj.value.color
      }
      return ''
    })
    return {
      name,
      className
    }
  }
})
</script>

<style scoped>

</style>
