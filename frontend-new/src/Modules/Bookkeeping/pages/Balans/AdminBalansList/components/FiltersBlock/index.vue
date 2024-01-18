<template>
  <div class="row items-center q-col-gutter-xs">
    <div>
      <q-input
        :model-value="modelValue.find"
        label="Найти участок"
        dense
        outlined
        clearable
        @update:model-value="setValue($event, 'find')"
      />
    </div>
    <div
      class="filter-item"
    >
      <q-select
        :model-value="modelValue.duty"
        :options="options"
        label="Долги"
        map-options
        clearable
        dense
        outlined
        emit-value
        transition-show="jump-up"
        transition-hide="jump-up"
        @update:model-value="setValue($event, 'duty')"
        @clear="setValue('', 'duty')"

      />
    </div>
    <RateGroupSelect
      :model-value="modelValue.rate_group_id"
      outlined
      :label="groupLabel"
      dense
      :disable="!modelValue.duty"
      clearable
      class="filter-item"
      @update:model-value="setValue($event, 'rate_group_id')"
    />
    <div style="width: 170px;">
      <InputNumber
        :model-value="modelValue.zeroLine"
        label="Сумма долга"
        :disable="!modelValue.duty"
        dense
        outlined
        clearable
        @update:model-value="setValue($event, 'zeroLine')"
        @clear="setValue(0, 'zeroLine')"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import PaymentErrorStatusSelect from 'src/Modules/Bookkeeping/components/Payment/PaymentErrorStatusSelect/index.vue'
import InputDateRange from 'components/Input/InputDateRange/index.vue'
import RateGroupSelect from 'src/Modules/Rate/components/RateGroupSelect/index.vue'
import InputNumber from 'src/components/Input/InputNumber/index.vue'

export default defineComponent({
  components: {
    PaymentErrorStatusSelect,
    RateGroupSelect,
    InputDateRange,
    InputNumber
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const groupLabel = computed(() => {
      if (props.modelValue.duty === 1) {
        return 'C долгами по'
      }
      if (props.modelValue.duty === 2) {
        return 'Без долгов по'
      }
      return ''
    })
    const options = computed(() => {
      if (props.modelValue.zeroLine !== 0) {
        return [
          {
            value: 1,
            label: 'C долгами ( > ' + props.modelValue.zeroLine + ' руб)'
          },
          {
            value: 2,
            label: 'Без долгов ( < ' + props.modelValue.zeroLine + ' руб)'
          },

        ]
      }
      return [
        {
          value: 1,
          label: 'C долгами'
        },
        {
          value: 2,
          label: 'Без долгов'
        },

      ]
    })
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
      if (field === 'duty' && !val) {
        tmp.rate_group_id = ''
      }
      emit('update:model-value', tmp)
    }
    const setDate = (val) => {
      emit('update:model-value', val)
    }
    return {
      data,
      groupLabel,
      options,
      setValue,
      setDate
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
