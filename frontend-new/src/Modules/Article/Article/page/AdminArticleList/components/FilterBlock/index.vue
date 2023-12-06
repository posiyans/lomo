<template>
  <div class="row items-center q-col-gutter-xs">
    <div>
      <q-input :model-value="modelValue.find" label="Поиск" dense outlined clearable class="filter-item" @update:model-value="setField($event, 'find')" />
    </div>
    <ArticleCategorySelect
      :model-value="modelValue.category"
      clearable
      outlined
      dense
      class="filter-item"
      @update:model-value="setField($event, 'category')"
    />
    <StatusSelect
      :model-value="modelValue.status"
      label="Статус"
      clearable
      dense
      outlined
      class="filter-item"
      @update:model-value="setField($event, 'status')"
    />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import StatusSelect from 'src/Modules/Article/Article/components/StatusSelect'
import ArticleCategorySelect from 'src/Modules/Article/Category/components/ArticleCategorySelect'

export default defineComponent({
  components: {
    StatusSelect,
    ArticleCategorySelect
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const setField = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
      emit('update:model-value', tmp)
    }
    return {
      data,
      setField
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
