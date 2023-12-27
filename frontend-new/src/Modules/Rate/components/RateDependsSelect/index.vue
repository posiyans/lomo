<template>
  <q-select
    :model-value="modelValue"
    :options="depends"
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
import { defineComponent, onMounted, ref } from 'vue'
import { getRateDependsList } from 'src/Modules/Rate/api/rateAdminApi'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      required: true
    },
    label: {
      type: String,
      default: 'Расчитывается по'
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
    const depends = ref([])
    onMounted(() => {
      getRateDependsList()
        .then(res => {
          depends.value = res.data.data
        })
    })
    const setValue = (val) => {
      emit('update:modelValue', val)
    }
    return {
      data,
      depends,
      setValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
