<template>
  <q-select
    :model-value="modelValue"
    :options="type"
    map-options
    emit-value
    :label="label"
    :dense="dense"
    :outlined="outlined"
    :clearable="clearable"
    option-value="id"
    option-label="label"
    @update:model-value="setValue"
  />
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useAppealType } from 'src/Modules/Appeal/hooks/useAppealType'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [Number, String],
      default: ''
    },
    label: {
      type: String,
      default: undefined
    },
    outlined: {
      type: Boolean,
      default: false
    },
    dense: {
      type: Boolean,
      default: false
    },
    clearable: {
      type: Boolean,
      default: false
    }

  },
  setup(props, { emit }) {
    const data = ref(null)
    const { type } = useAppealType()
    const setValue = (val) => {
      emit('update:model-value', val)

    }
    onMounted(() => {

    })
    return {
      data,
      setValue,
      type
    }
  }
})
</script>

<style scoped>

</style>
