<template>
  <div v-if="showText">
    <slot v-bind:label="showText">
      {{ showText }}
    </slot>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref } from 'vue'
import { useMessage } from 'src/Modules/Comments/hooks/useMessage'

export default defineComponent({
  components: {},
  props: {
    type: {
      type: String,
      default: 'article'
    },
    objectUid: {
      type: [String, Number],
      required: true
    },
    hideZero: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const { getCountMessage, countMessage } = useMessage()
    const showText = computed(() => {
      if (props.hideZero && countMessage.value === 0) {
        return ''
      }
      return countMessage
    })
    onMounted(() => {
      getCountMessage(props.type, props.objectUid)
    })

    return {
      data,
      showText
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

