<template>
  <div>
    <q-checkbox
      :model-value="modelValue.with_trashed"
      label="Все"
      :false-value="0"
      :true-value="1"
      @update:model-value="setValue($event, 'with_trashed')" />
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const setValue = (val, name) => {
      const data = Object.assign({}, props.modelValue)
      data[name] = val
      emit('update:model-value', data)
    }
    return {
      setValue
    }
  }
})
</script>

<style scoped>

</style>
