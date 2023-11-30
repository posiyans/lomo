<template>
  <component
    :is="componentName"
    :model-value="modelValue"
    :outlined="outlined"
    :dense="dense"
    :label="item.label"
    :rules="rules"
    :style="styleName"
    @update:model-value="setValue"
  />
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import InputDate from 'src/components/Input/InputDate/index.vue'
import InputPhone from 'src/components/Input/InputPhone/index.vue'
import BooleanFiled from './BooleanFiled.vue'
import { isEmail, required } from 'src/utils/validators.js'

export default defineComponent({
  components: {
    InputDate,
    InputPhone,
    BooleanFiled
  },
  props: {
    modelValue: {
      required: true
    },
    dense: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    item: {
      type: Object,
      required: true
    },

  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const styleName = computed(() => {
      if (['boolean', 'phone', 'date'].includes(props.item.type)) {
        return 'max-width: 320px;'
      }
      return ''
    })
    const rules = computed(() => {
      const r = []
      if (props.item.rules.includes('isEmail')) {
        r.push(isEmail)
      }
      if (props.item.rules.includes('required')) {
        r.push(required)
      }
      return r
    })
    const componentName = computed(() => {
      if (props.item.type === 'phone') {
        return InputPhone
      } else if (props.item.type === 'date') {
        return InputDate
      } else if (props.item.type === 'boolean') {
        return BooleanFiled
      } else {
        return 'q-input'
      }
    })
    const setValue = (val) => {
      // const tmp = Object.assign({}, props.modelValue)
      // tmp[props.item.name] = val
      emit('update:model-value', val)
    }
    onMounted(() => {

    })
    return {
      data,
      setValue,
      styleName,
      componentName,
      rules
    }
  }
})
</script>

<style scoped>

</style>
