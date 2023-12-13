<template>
  <q-input
    :model-value="modelValue"
    :outlined="outlined"
    :clearable="clearable"
    :label="label"
    :dense="dense"
    :mask="mask"
    :rules="rules"
    @update:model-value="setPhone"
  />
</template>

<script>
import { dataphone } from './data.js'
import { nextTick } from 'vue'

export default {
  props: {
    modelValue: String,
    label: {
      type: String,
      default: 'Номер телефона'
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
    },
    rules: {
      type: [Function, Array],
      default: () => {
      }
    }
  },
  data() {
    return {
      timer: null,
      text: '',
      phoneCodeArray: dataphone()
    }
  },
  computed: {
    mask() {
      if (this.code) {
        return '+' + '#'.repeat(this.code.dialCode.length) + '(###)###-##-##'
      }
      return '+#(###)###-##-########'
    },
    phone() {
      if (this.modelValue && this.modelValue.replace) {
        return this.modelValue.replace(/[^\d]/g, '')
      }
      return ''
    },
    code() {
      let code = null
      this.phoneCodeArray.forEach(item => {
        if (this.phone.startsWith(item.dialCode)) {
          if (!code && item.priority === 0) {
            code = item
          } else if (item.priority > 0) {
            item.areaCodes.forEach(area => {
              if (this.phone.startsWith(item.dialCode + area)) {
                code = Object.assign({}, item)
                code.dialCode = item.dialCode + area
              }
            })
          }
        }
      })
      return code
    }
  },
  methods: {
    setPhone(val) {
      // вычисление маски требует время и в этот момент действует дефотная что приводит к искажением данных, поэтому откладываем возвращение данных
      nextTick(() => {
        this.$emit('update:model-value', val)
      })
    }
  }
}
</script>

<style scoped>

</style>
