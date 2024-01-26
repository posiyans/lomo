<template>
  <div class="row items-center q-col-gutter-xs">
    <RateGroupSelect
      :model-value="modelValue.rate_group_id"
      :params="{depends: 2}"
      label="Тип прибора"
      outlined
      dense
      auto-select
      class="filter-item"
      @update:model-value="setValue($event, 'rate_group_id')"
    />
    <RateTypeForGroupSelect
      :model-value="modelValue.rate_type_id"
      :rate-group="modelValue.rate_group_id"
      label="Тариф прибора"
      outlined
      dense
      class="filter-item"
      @update:model-value="setValue($event, 'rate_type_id')"
    />
    <InputDateRange
      :model-value="modelValue"
      dense
      outlined
      clearable
      @update:model-value="setRange" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import RateTypeForGroupSelect from 'src/Modules/Rate/components/RateTypeForGroupSelect/index.vue'
import InputDateRange from 'components/Input/InputDateRange/index.vue'

export default defineComponent({
  components: {
    RateGroupSelect,
    RateTypeForGroupSelect,
    InputDateRange
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const rateGroup = ref('')
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
      emit('update:model-value', tmp)
    }
    const setRange = (val) => {
      val.page = 1
      emit('update:model-value', val)
    }
    return {
      data,
      setValue,
      setRange,
      rateGroup
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
