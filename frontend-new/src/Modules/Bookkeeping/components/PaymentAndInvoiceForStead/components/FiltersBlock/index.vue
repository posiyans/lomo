<template>
  <div class="row items-center q-col-gutter-xs">
    <RateGroupSelect
      :model-value="modelValue.rate_group_id"
      outlined
      dense
      label="Группа"
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'rate_group_id')"
    />
    <q-select
      :model-value="modelValue.type"
      :options="[
        {
          value: 'payment',
          label: 'Только платежи'
        },
        {
          value: 'invoice',
          label: 'Только счета'
        }
      ]"
      map-options
      emit-value
      outlined
      label="Тип"
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'type')"
    />
    <q-select
      :model-value="modelValue.is_paid"
      :options="[
        {
          value: 'paid',
          label: 'Только оплаченные'
        },
        {
          value: 'no_paid',
          label: 'Только не оплаченные'
        }
      ]"
      map-options
      emit-value
      outlined
      label="Оплата"
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'is_paid')"
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
