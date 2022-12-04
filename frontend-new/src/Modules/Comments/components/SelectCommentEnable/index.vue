<template>
  <div>
    <q-select
      :modelValue="modelValue"
      outlined
      :options="options"
      :label="label"
      map-options
      dense
      emit-value
      @update:modelValue="setValue"
    />
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      default: 'Комментарии'
    }
  },
  setup(_, { emit }) {
    const setValue = (val) => {
      emit('update:modelValue', val)
    }
    const options = [
      {
        value: 0,
        label: 'отключены'
      },
      {
        value: 1,
        label: 'разрешены всем'
      }
    ]
    return {
      setValue,
      options
    }
  }
})
</script>

<style scoped>

</style>
