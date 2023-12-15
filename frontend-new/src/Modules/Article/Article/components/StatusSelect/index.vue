<template>
  <div>
    <q-select
      :model-value="filterValue"
      :options="status"
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
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { getStatusList } from 'src/Modules/Article/Article/api/article'

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
    const status = ref([])
    const getData = () => {
      getStatusList()
        .then(res => {
          status.value = res.data
        })
    }
    const filterValue = computed(() => {
      if (props.modelValue) {
        return +props.modelValue
      }
      return ''
    })
    const setValue = (val) => {
      emit('update:model-value', val)
    }
    onMounted(() => {
      getData()
    })
    return {
      status,
      filterValue,
      getData,
      setValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
