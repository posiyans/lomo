<template>
  <div class="row items-center q-col-gutter-xs">
    <SteadSelect
      :model-value="modelValue.stead_id"
      outlined
      label="Участок"
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'stead_id')"
    />
    <RateGroupSelect
      :model-value="modelValue.rate_group_id"
      outlined
      label="Тип"
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'rate_group_id')"
    />
    <InvoiceStatusSelect
      :model-value="modelValue.is_paid"
      outlined
      label="Статус"
      dense
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'is_paid')"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import InvoiceStatusSelect from 'src/Modules/Bookkeeping/components/Invoice/InvoiceStatusSelect/index.vue'

export default defineComponent({
  components: {
    SteadSelect,
    RateGroupSelect,
    InvoiceStatusSelect
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
