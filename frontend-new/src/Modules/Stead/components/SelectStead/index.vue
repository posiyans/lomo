<template>
  <div>
    <q-select
        :key="key"
        :model-value="modelValue"
        :options="SteadsList"
        :label="label"
        :loading="loading"
        use-input
        hide-selected
        fill-input
        use-chips
        map-options
        :clearable="clearable"
        :dense="dense"
        :outlined="outlined"
        emit-value
        transition-show="jump-up"
        transition-hide="jump-up"
        option-label="number"
        option-value="id"
        input-debounce="0"
        @filter="filterFn"
        @update:model-value="setValue"
        @clear="setValue('')"
    />
  </div>
</template>

<script>
import { getSteadsList } from 'src/Modules/Stead/api/stead.js'

export default {
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
    readOnly: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      SteadsList: [],
      key: 1,
      loading: false
    }
  },
  mounted () {
    if (this.modelValue) {
      this.loading = true
      const data = {
        id: this.modelValue
      }
      getSteadsList(data)
        .then(response => {
          this.SteadsList = response.data.data
          if (this.SteadsList.length === 1) {
            this.setValue(this.SteadsList[0].id)
          }
          this.loading = false
          this.key++
        })
    }
  },
  methods: {
    filterFn (val, update, abort) {
      const data = {
        find: val
      }
      this.loading = true
      getSteadsList(data)
        .then(response => {
          this.SteadsList = response.data.data
          if (this.SteadsList.length === 1) {
            this.setValue(this.SteadsList[0].id)
          }
          this.loading = false
          update()
        })
    },
    setValue (val) {
      this.$emit('update:modelValue', val)
    },
    findStead (val = '') {
      const data = {
        find: val
      }
      getSteadsList(data)
        .then(response => {
          this.SteadsList = response.data.data
        })
    }
  }
}
</script>

<style scoped>

</style>
