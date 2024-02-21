<template>
  <q-select
    :model-value="modelValue"
    :options="showType"
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
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useAppealType } from 'src/Modules/Appeal/hooks/useAppealType'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [Number, String],
      default: ''
    },
    public: {
      type: Boolean,
      default: false
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
    const showType = computed(() => {
      if (type && props.public) {
        return type.value.filter(i => i.public)
      }
      return type.value
    })
    const setValue = (val) => {
      emit('update:model-value', val)
    }
    onMounted(() => {

    })
    return {
      data,
      setValue,
      type,
      showType
    }
  }
})
</script>

<style scoped>

</style>
