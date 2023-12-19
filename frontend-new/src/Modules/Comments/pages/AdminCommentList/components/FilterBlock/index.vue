<template>
  <div>
    <q-input
      :model-value="modelValue.find"
      dense
      outlined
      label="Найти"
      @update:model-value="setValue($event, 'find')"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
      emit('update:model-value', tmp)
    }
    return {
      data,
      setValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
