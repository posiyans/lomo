<template>
  <div class="row items-center q-col-gutter-xs q-mb-xs">
    <div>
      <q-input :model-value="modelValue.find" placeholder="Поиск" clearable outlined dense @update:model-value="setValue($event, 'find')" />
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
      return emit('update:model-value', tmp)
    }
    return {
      setValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
