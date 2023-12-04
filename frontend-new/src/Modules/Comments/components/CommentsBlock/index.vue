<template>
  <div>
    <MessageBlock :message-block="messageBlock" :no-ban="noBan" :no-delete="noDelete" class="comments-block" />
    <SendCommentBlock :message-block="messageBlock" class="bg-grey-3" />
  </div>
</template>

<script>

import { defineComponent, onMounted } from 'vue'
import SendCommentBlock from 'src/Modules/Comments/components/SendCommentBlock/index.vue'
import MessageBlock from 'src/Modules/Comments/components/MessageBlock/index.vue'
import { useMessageBlock } from 'src/Modules/Comments/hooks/useMessageBlock'

export default defineComponent({
  components: {
    SendCommentBlock,
    MessageBlock
  },
  props: {
    noBan: {
      type: Boolean,
      default: false
    },
    noDelete: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'article'
    },
    objectUid: {
      type: [String, Number],
      required: true
    }
  },
  setup(props) {
    const messageBlock = useMessageBlock(props.type, props.objectUid)

    onMounted(() => {

    })
    return {
      messageBlock
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
