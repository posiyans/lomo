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
    const setField = (val) => {
      console.log(val)
      const tmp = val.replace(/[^0-9.]/g, '')
      // emit('update:model-value', tmp)
    }
    return {
      setField
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
