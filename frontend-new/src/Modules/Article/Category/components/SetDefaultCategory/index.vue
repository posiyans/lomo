<template>
  <div>
    <q-btn color="primary" label="Установить по умолчанию" @click="setDefault" />
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { updateCategory } from 'src/Modules/Article/Category/api/category'

export default defineComponent({
  components: {},
  props: {
    categoryId: {
      type: [String, Number],
      required: true
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const setDefault = () => {
      const data = {
        setDefault: true
      }
      updateCategory(props.categoryId, data)
        .then(res => {
          emit('success')
        })
    }
    return {
      data,
      setDefault
    }
  }
})
</script>

<style scoped>

</style>
