<template>
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
      default: 'Период оплаты'
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
    const data = ref(false)
    const options = ref([
      { label: 'ежемесячно', value: 1 },
      { label: 'ежеквартально', value: 3 },
      { label: 'раз в пол года', value: 6 },
      { label: 'ежегодно', value: 12 }
    ])
    const setValue = (val) => {
      emit('update:modelValue', val)
    }
    return {
      data,
      options,
      setValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
