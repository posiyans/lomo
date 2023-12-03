<template>
  <q-select
    :model-value="modelValue"
    :options="status"
    map-options
    emit-value
    :label="label"
    :dense="dense"
    :outlined="outlined"
    :clearable="clearable"
    option-value="value"
    option-label="label"
    @update:model-value="setValue"
  />
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useAppealStatus } from 'src/Modules/Appeal/hooks/useAppealStatus'

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
    const { status } = useAppealStatus()
    const setValue = (val) => {
      emit('update:model-value', val)

    }
    onMounted(() => {

    })
    return {
      data,
      setValue,
      status
    }
  }
})
</script>

<style scoped>

</style>
