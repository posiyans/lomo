<template>
  <div>
    {{ title }}
  </div>
</template>

<script>
import { computed, defineComponent, onMounted } from 'vue'
import { userCategoryStore } from 'src/Modules/Article/Category/stores/useCategoryStore'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [Number, String],
      default: null
    }
  },
  setup(props) {
    const categoryStore = userCategoryStore()
    onMounted(() => {
      categoryStore.getData()
    })
    const item = computed(() => {
      return categoryStore.categoryList.find(item => item.id === +props.modelValue)
    })
    const title = computed(() => {
      if (item.value) {
        return item.value.name
      }
      return ''
    })

    return {
      categoryStore,
      title
    }
  }
})
</script>

<style scoped>

</style>
