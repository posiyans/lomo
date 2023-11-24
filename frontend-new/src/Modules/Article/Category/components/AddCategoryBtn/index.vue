<template>
  <div>
    <div @click="dialogVisibleAction">
      <slot>
        <q-btn icon="add" color="primary" flat />
      </slot>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {},
  setup(props, { emit }) {
    const data = ref(false)
    const dialogVisibleAction = () => {

    }
    return {
      data,
      dialogVisibleAction
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
