<template>
  <div>
    <q-input
      :model-value="modelValue.find"
      outlined
      label="Найти участок"
      @update:model-value="setValue($event, 'find')" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const setValue = (val, field) => {
      const tmp = Object.assign({}, props.modelValue)
      tmp[field] = val
      tmp.page = 1
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
