<template>
  <div class="row items-center q-col-gutter-xs">
    <div>
      <q-input
        :model-value="modelValue.find"
        label="Найти платеж"
        dense
        outlined
        clearable
        @update:model-value="setValue($event, 'find')"
      />
    </div>
    <RateGroupSelect
      :model-value="modelValue.rate_group_id"
      outlined
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'rate_group_id')"
    />
    <PaymentErrorStatusSelect
      :model-value="modelValue.is_error"
      outlined
      label="Состояние"
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'is_error')"
    />
    <InputDateRange
      :model-value="modelValue"
      dense
      clearable
      outlined
      @update:model-value="setDate"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import PaymentErrorStatusSelect from 'src/Modules/Bookkeeping/components/Payment/PaymentErrorStatusSelect/index.vue'
import InputDateRange from 'components/Input/InputDateRange/index.vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'

export default defineComponent({
  components: {
    PaymentErrorStatusSelect,
    RateGroupSelect,
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
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
      emit('update:model-value', tmp)
    }
    const setDate = (val) => {
      emit('update:model-value', val)
    }
    return {
      data,
      setValue,
      setDate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
