<template>
  <div class="row items-center q-col-gutter-sm">
    <div class="filter-item">
      <q-input
        :model-value="modelValue.find"
        outlined
        dense
        label="Поиск"
        @update:model-value="setValue($event, 'find')"
      />

    </div>
    <div class="filter-item">
      <AppealTypeSelect
        :model-value="modelValue.type"
        outlined
        dense
        clearable
        label="Тип"
        @update:model-value="setValue($event, 'type')"
      />
    </div>
    <div class="filter-item">
      <AppealStatusSelect
        :model-value="modelValue.status"
        outlined
        dense
        clearable
        label="Статус"
        @update:model-value="setValue($event, 'status')"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppealTypeSelect from 'src/Modules/Appeal/components/AppealTypeSelect/index.vue'
import AppealStatusSelect from 'src/Modules/Appeal/components/AppealStatusSelect/index.vue'

export default defineComponent({
  components: {
    AppealTypeSelect,
    AppealStatusSelect
  },
  props: {
    modelValue: {
      type: Object
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      emit('update:model-value', tmp)
    }
    onMounted(() => {

    })
    return {
      data,
      setValue
    }
  }
})
</script>

<style scoped>

</style>
