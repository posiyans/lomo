<template>
  <div>
    <q-select
      :model-value="modelValue"
      :options="options"
      :label="label"
      :loading="loading"
      use-input
      hide-selected
      fill-input
      map-options
      :clearable="clearable"
      :dense="dense"
      :outlined="outlined"
      emit-value
      transition-show="jump-up"
      transition-hide="jump-up"
      option-label="number"
      option-value="id"
      @filter="filterFn"
      @update:model-value="setValue"
      @clear="setValue('')"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getSteadsList } from 'src/Modules/Stead/api/stead.js'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      required: true
    },
    label: {
      type: String,
      default: 'Участок'
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
    const SteadsList = ref([])
    const options = ref([])
    const key = ref(1)
    const loading = ref(false)
    const getData = () => {
      loading.value = true
      getSteadsList()
        .then(response => {
          SteadsList.value = response.data.data
          options.value = response.data.data
          if (props.autoSelect && SteadsList.value.length === 1) {
            setValue(this.SteadsList[0].id)
          }
          loading.value = false
          key.value++
        })
    }
    const setValue = (val) => {
      emit('update:model-value', val)
    }
    const filterFn = (val, update, abort) => {
      update(() => {
        const needle = val.toLowerCase()
        options.value = SteadsList.value.filter(v => v.number.toLowerCase().indexOf(needle) > -1)
        if (props.autoSelect && options.value.length === 1) {
          setValue(options.value[0].id)
        }
      })
    }
    onMounted(() => {
      getData()
    })
    return {
      filterFn,
      setValue,
      options,
      key,
      loading
    }
  }
})
</script>

<style scoped>

</style>
