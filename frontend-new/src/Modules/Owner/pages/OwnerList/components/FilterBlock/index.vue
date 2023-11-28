<template>
  <div class="row items-center q-col-gutter-sm">
    <div>
      <q-input
        :model-value="modelValue.find"
        label="Поиск"
        outlined
        dense
        @update:model-value="setValue($event, 'find')"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
      emit('update:model-value', tmp)
    }
    return {
      data,
      setValue
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
