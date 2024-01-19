<template>
  <q-input
    :model-value="modelValue"
    :label="label"
    :outlined="outlined"
    :dense="dense"
    :clearable="clearable"
    :rules="rules"
    input-style="text-align: center;"
    @update:model-value="setField"
  >
    <template v-slot:prepend>
      <q-btn color="negative" flat :dense="dense" @click="decrement" icon="remove" />
    </template>
    <template v-slot:append>
      <q-btn color="secondary" flat :dense="dense" @click="increment" icon="add" />
    </template>
  </q-input>
</template>

<script>

export default {
  props: {
    modelValue: [String, Number],
    label: {
      type: String,
      default: undefined
    },
    dense: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    clearable: {
      type: Boolean,
      default: false
    },
    rules: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    decrement() {
      let tmp = this.modelValue
      this.setField(--tmp)
    },
    increment() {
      let tmp = this.modelValue
      this.setField(++tmp)
    },
    setField(val) {
      let tmp = 0
      if (typeof val === 'string' || val instanceof String) {
        tmp = val.replace(/[^0-9.-]/g, '')
        // val = +(val.replace(/[^0-9.]/g, ''))
      } else {
        tmp = val
      }
      this.$emit('update:model-value', tmp)
    }
  }
}
</script>

<style scoped>

</style>
