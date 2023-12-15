<template>
  <div>
    <div>
      <div v-if="messageBlock.messageList.value.length > 0" class="q-mt-md">
        <q-btn
          color="grey-2"
          text-color="primary"
          icon="message"
          :label="messageBlock.messageList.value.length"
          unelevated
        />
      </div>
    </div>
    <q-separator />
    <div v-if="showChat">
      <MessageBlock :message-block="messageBlock" class="q-pa-md comments-block" />
      <SendCommentBlock :message-block="messageBlock" class="bg-grey-3" />
    </div>
  </div>

</template>

<script>
import { computed, defineComponent } from 'vue'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'
import SendCommentBlock from 'src/Modules/Comments/components/SendCommentBlock/index.vue'
import MessageBlock from 'src/Modules/Comments/components/MessageBlock/index.vue'
import { useMessageBlock } from 'src/Modules/Comments/hooks/useMessageBlock'

export default defineComponent({
  components: {
    SendCommentBlock,
    MessageBlock
  },
  props: {
    article: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const authStore = useAuthStore()
    const messageBlock = useMessageBlock('article', props.article.uid)
    const showChat = computed(() => {
      if (props.article.allow_comments === 1) {
        return false
      }
      if (props.article.allow_comments === 3) {
        return authStore.permissions.includes('owner')
      }
      return true
    })
    return {
      messageBlock,
      showChat
    }
  }
})
</script>

<style scoped>

</style>
