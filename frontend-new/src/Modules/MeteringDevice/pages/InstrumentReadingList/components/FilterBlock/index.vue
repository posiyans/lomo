<template>
  <div class="row items-center q-col-gutter-xs">
    <SteadSelect
      :model-value="modelValue.stead_id"
      label="Участок"
      outlined
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'stead_id')"
    />
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
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'rate_type_id')"
    />
    <InputDateRange
      :model-value="modelValue"
      dense
      outlined
      clearable
      @update:model-value="setRange" />
    <div>
      <q-checkbox
        :model-value="modelValue.group_by"
        label="Группировать"
        true-value="date"
        false-value=""
        @update:model-value="setValue($event, 'group_by')"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import RateTypeForGroupSelect from 'src/Modules/Rate/components/RateTypeForGroupSelect/index.vue'
import InputDateRange from 'components/Input/InputDateRange/index.vue'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'

export default defineComponent({
  components: {
    RateGroupSelect,
    RateTypeForGroupSelect,
    InputDateRange,
    SteadSelect
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
