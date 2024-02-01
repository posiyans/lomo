<template>
  <q-input
    :model-value="modelValue"
    :label="label"
    outlined
    @update:model-value="setField"
  >
  </q-input>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'

export default defineComponent({
  components: {},
  props: {
    modelValue: [String, Number],
    label: {
      type: String,
      default: undefined
    }
  },
  setup(props, { emit }) {
    let timer = null
    const setField = (val) => {

      if (timer) clearTimeout(timer)
      timer = setTimeout(() => {
        let tmp = val.replace(',', '.')
        tmp = tmp.replace(/[^0-9\\.-]+/g, '')
        emit('update:model-value', tmp)
      }, 200)
    }

    return {
      setField
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
