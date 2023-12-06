<template>
  <div>
    <q-select
      :model-value="modelValue"
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
import { getStatusList } from 'src/Modules/Article/Article/api/article'

export default {
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
  data() {
    return {
      status: []
    }
  },
  created() {
    this.getData()
  },
  methods: {
    getData() {
      getStatusList()
        .then(res => {
          this.status = res.data
        })
    },
    setValue(val) {
      this.$emit('update:model-value', val)
      this.$emit('input', val)
    }
  }
}
</script>

<style scoped>

</style>
