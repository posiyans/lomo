<template>
  <div :style="style">{{ title }}</div>
</template>

<script>
import { computed, defineComponent, onMounted } from 'vue'
import { useArticleStatusStore } from 'src/Modules/Article/Article/stores/useArticleStatusStore'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [Number, String],
      default: null
    },
    color: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const articleStatusStore = useArticleStatusStore()
    onMounted(() => {
      articleStatusStore.getData()
    })
    const item = computed(() => {
      return articleStatusStore.statusList.find(item => item.id === props.modelValue)
    })
    const title = computed(() => {
      if (item.value) {
        return item.value.label
      }
      return ''
    })
    const style = computed(() => {
      if (props.color && item.value) {
        return 'color: ' + item.value.color + ';'
      }
      return ''
    })

    return {
      title,
      style
    }
  }
})
</script>

<style scoped>

</style>
