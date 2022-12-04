<template>
  <q-select
    :outlined="outlined"
    :dense="dense"
    :model-value="modelValue"
    :options="votingType"
    map-options
    emit-value
    :label="label"
    @update:modelValue="setvalue"
  />
</template>

<script>
import { defineComponent } from 'vue'
import { votingType } from 'src/Modules/Voting/constant'

export default defineComponent({
  props: {
    modelValue: {
      type: String
    },
    label: {
      type: String,
      default: 'Тип голосования'
    },
    dense: {
      type: Boolean,
      default: false
    },
    outlined: {
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
      votingType
    }
  }
})
</script>

<style scoped>

</style>
