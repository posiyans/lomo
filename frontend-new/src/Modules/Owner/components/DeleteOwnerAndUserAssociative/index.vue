<template>
  <div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    owner: {
      type: Object
    },
    user: {}
  },
  setup(props, { emit }) {
    const data = ref(false)
    return {
      data
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
