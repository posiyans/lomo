<template>
  <div>
    <MessageBlock :message-block="messageBlock" no-ban no-delete no-repeat class="comments-block" />
    <SendCommentBlock v-if="appealOpen" :message-block="messageBlock" class="bg-grey-3" />
  </div>
</template>

<script>

import { computed, defineComponent, onMounted } from 'vue'
import SendCommentBlock from 'src/Modules/Comments/components/SendCommentBlock/index.vue'
import MessageBlock from 'src/Modules/Comments/components/MessageBlock/index.vue'
import { useMessageBlock } from 'src/Modules/Comments/hooks/useMessageBlock.js'

export default defineComponent({
  components: {
    SendCommentBlock,
    MessageBlock
  },
  props: {
    appeal: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const messageBlock = useMessageBlock('appeal', props.appeal.uid)
    const appealClose = computed(() => {
      return props.appeal.status === 'close'
    })
    const appealOpen = computed(() => {
      return !appealClose.value
    })
    onMounted(() => {

    })
    return {
      messageBlock,
      appealClose,
      appealOpen
    }
  }
})

</script>

<style scoped>
.comments-block {
  /*  max-height: 450px;*/
  /*  overflow: hidden;*/
  /*  overflow-y: auto;*/
}
</style>
