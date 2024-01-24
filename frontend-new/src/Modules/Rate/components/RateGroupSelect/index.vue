<template>
  <div>
    <q-select
      :model-value="modelValue"
      :options="options"
      :label="label"
      :loading="loading"
      map-options
      :clearable="clearable"
      :dense="dense"
      :outlined="outlined"
      :disable="disable"
      emit-value
      transition-show="jump-up"
      transition-hide="jump-up"
      option-label="name"
      option-value="id"
      @update:model-value="setValue"
      @clear="setValue('')"
      :rules="rules"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { getRateGroupList } from 'src/Modules/Rate/api/rateAdminApi'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [String, Number],
      required: true
    },
    label: {
      type: String,
      default: 'Тип'
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
    disable: {
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
    },
    rules: {
      type: Array,
      default: () => []
    },
    params: {
      type: Object,
      default: () => {
      }
    }
  },
  setup(props, { emit }) {
    const options = ref([])
    const loading = ref(false)
    const getData = () => {
      loading.value = true
      getRateGroupList(props.params)
        .then(response => {
          options.value = response.data.data
          loading.value = false
          if (props.autoSelect && options.value.length === 1) {
            setValue(options.value[0].id)
          }
        })
    }
    const setValue = (val) => {
      emit('update:model-value', val)
    }

    onMounted(() => {
      getData()
    })
    return {
      setValue,
      options,
      loading
    }
  }
})
</script>

<style scoped>

</style>
