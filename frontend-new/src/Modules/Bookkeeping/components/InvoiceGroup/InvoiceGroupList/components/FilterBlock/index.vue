<template>
  <div class="row items-center q-col-gutter-xs">
    <RateGroupSelect
      :model-value="modelValue.rate_group_id"
      outlined
      label="Тип"
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'rate_group_id')"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'

export default defineComponent({
  components: {
    RateGroupSelect
  },
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
