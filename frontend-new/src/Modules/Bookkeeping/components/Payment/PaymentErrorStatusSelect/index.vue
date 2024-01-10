<template>
  <div>
    <q-select
      :model-value="modelValue"
      :options="options"
      :label="label"
      map-options
      :clearable="clearable"
      :dense="dense"
      :outlined="outlined"
      emit-value
      transition-show="jump-up"
      transition-hide="jump-up"
      @update:model-value="setValue"
      @clear="setValue('')"
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
      type: [String, Number],
      required: true
    },
    label: {
      type: String,
      default: 'Статус'
    },
    clearable: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    dense: {
      type: Boolean,
      default: false
    },
    autoSelect: {
      type: Boolean,
      default: false
    },
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const options = ref([
      {
        value: 1,
        label: 'Только с ошибками'
      },
      {
        value: 0,
        label: 'Только без ошибок'
      }

    ])

    const setValue = (val) => {
      emit('update:model-value', val)
    }
    return {
      setValue,
      options
    }
  }
})
</script>

<style scoped>

</style>
