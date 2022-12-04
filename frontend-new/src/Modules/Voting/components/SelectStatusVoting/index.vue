<template>
  <q-select
    :outlined="outlined"
    :dense="dense"
    :model-value="modelValue"
    :options="votingStatus"
    map-options
    emit-value
    :use-chips="useChips"
    :clearable="clearable"
    :multiple="multiple"
    :label="label"
    @update:modelValue="setvalue"
  />
</template>

<script>
import { defineComponent } from 'vue'
import { votingStatus } from 'src/Modules/Voting/constant.js'

export default defineComponent({
  props: {
    modelValue: {
      type: [String, Array]
    },
    label: {
      type: String,
      default: 'Статус голосования'
    },
    dense: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    multiple: {
      type: Boolean,
      default: false
    },
    clearable: {
      type: Boolean,
      default: false
    },
    useChips: {
      type: Boolean,
      default: false
    }
  },
  setup (_, { emit }) {
    const setvalue = (val) => {
      emit('update:modelValue', val)
    }
    return {
      setvalue,
      votingStatus
    }
  }
})
</script>

<style scoped>

</style>
