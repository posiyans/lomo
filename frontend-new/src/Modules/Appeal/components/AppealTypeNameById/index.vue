<template>
  <div>
    {{ label }}
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAppealType } from 'src/Modules/Appeal/hooks/useAppealType'

export default defineComponent({
  components: {},
  props: {
    typeId: {
      type: [String, Number],
      default: ''
    }
  },
  setup(props, { emit }) {
    const data = ref(null)
    const router = useRouter()
    const route = useRoute()
    const { type } = useAppealType()
    const typeObj = computed(() => {
      return type.value.find(item => {
        if (item.id === props.typeId) {
          return true
        }
      })
    })
    const label = computed(() => {
      return typeObj?.value?.label || ''
    })
    onMounted(() => {

    })
    return {
      data,
      label,
      typeObj
    }
  }
})
</script>

<style scoped>

</style>
