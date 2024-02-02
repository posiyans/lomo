<template>
  <div>
    {{ title }}
  </div>
</template>

<script>
import { computed, defineComponent, onMounted, watch } from 'vue'
import { userCategoryStore } from 'src/Modules/Article/Category/stores/useCategoryStore'
import { LocalStorage } from 'quasar'

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
    watch(
      () => title.value,
      () => {
        const txt = title.value ? `${title.value} - ` : ''
        document.title = txt + LocalStorage.getItem('SiteName') || 'СНТ'
      }
    )
    return {
      categoryStore,
      title
    }
  }
})
</script>

<style scoped>

</style>
