<template>
  <div>
    <q-select
      outlined
      :model-value="modelValue"
      :options="[
        {
         label: 'Нет',
         value:  false
        },
        {
           label: 'Да',
           value:  true
        }
     ]"
      map-options
      emit-value
      :rules="rules"
      :label="label"
      @update:model-value="setValue"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    rules: {
      type: Array,
      default: () => []
    },
    label: {
      type: String,
      default: null
    }
  },
  setup(props, { emit }) {
    const setValue = (val) => {
      emit('update:model-value', val)
    }
    return {
      setValue
    }
  }
})
</script>

<style scoped>

</style>
