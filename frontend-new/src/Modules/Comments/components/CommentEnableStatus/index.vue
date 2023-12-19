<template>
  <div v-if="status" :class="className">
    {{ status.label }}
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { getCommentsStatusList } from 'src/Modules/Comments/api/commentApi'

export default defineComponent({
  components: {},
  props: {
    modelValue: {
      type: [Number, String],
      default: ''
    },
    color: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const data = ref(null)
    const options = getCommentsStatusList()
    const item = computed(() => {
      return options.filter(item => {
        return item.value === props.modelValue
      })
    })
    const status = computed(() => {
      if (item.value) {
        return item.value[0]
      }
      return {}
    })
    const className = computed(() => {
      if (props.color && status.value?.color) {
        return 'text-' + status.value.color
      }
      return ''
    })
    onMounted(() => {

    })
    return {
      className,
      status,
      data
    }
  }
})
</script>

<style scoped>

</style>
