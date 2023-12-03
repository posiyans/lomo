<template>
  <div :class="className">
    {{ label }}
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAppealStatus } from 'src/Modules/Appeal/hooks/useAppealStatus'

export default defineComponent({
  components: {},
  props: {
    status: {
      type: String,
      default: ''
    },
    addColor: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const { status } = useAppealStatus()
    const typeObj = computed(() => {
      return status.value.find(item => {
        if (item.value === props.status) {
          return true
        }
      })
    })
    const label = computed(() => {
      return typeObj?.value?.label || ''
    })
    const className = computed(() => {
      if (props.addColor) {
        return typeObj?.value?.color || ''
      }
      return ''
    })
    onMounted(() => {

    })
    return {
      data,
      label,
      className,
      typeObj
    }
  }
})
</script>

<style scoped>

</style>
