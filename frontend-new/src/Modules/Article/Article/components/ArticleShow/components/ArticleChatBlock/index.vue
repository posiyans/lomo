<template>
  <div v-if="showChat || messageBlock.messageList.value.length" ref="commentsRef">
    <div>
      <div class="q-mt-md">
        <q-btn
          color="grey-2"
          text-color="primary"
          icon="message"
          :label="messageBlock.messageList.value.length || ''"
          unelevated
        />
      </div>
    </div>
    <q-separator />
    <div style="max-width: 100vw">
      <MessageBlock :message-block="messageBlock" />
      <SendCommentBlock v-if="showChat" :message-block="messageBlock" file class="bg-grey-3" />
      <div v-else class="text-center bg-grey-3 q-py-md text-secondary text-weight-bold">
        {{ errorMessage }}
      </div>
    </div>
  </div>

</template>

<script>
import { computed, defineComponent, ref, watch } from 'vue'
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
    },
    scroll: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const commentsRef = ref(null)
    const authStore = useAuthStore()
    const messageBlock = useMessageBlock('article', props.article.uid)
    const errorMessage = computed(() => {
      if (props.article.allow_comments === 1) {
        return 'Комментарии отключены'
      }
      if (props.article.allow_comments === 3 && !authStore.roles.includes('owner')) {
        return 'Комментировать могут только собственники'
      }
      return ''
    })
    const showChat = computed(() => {
      if (props.article.allow_comments === 1) {
        return false
      }
      if (props.article.allow_comments === 3) {
        return authStore.roles.includes('owner')
      }
      return true
    })
    const scrollToMessage = () => {
      if (props.scroll && messageBlock.messageLoading) {
        const el = commentsRef
        if (el.value) {
          const top = el.value.offsetTop - 100
          if (top) {
            window.scrollTo(0, top)
          }
        }
      }
    }
    watch(
      () => messageBlock.messageLoading.value,
      () => scrollToMessage()
    )
    return {
      messageBlock,
      commentsRef,
      showChat,
      errorMessage
    }
  }
})
</script>

<style scoped>

</style>
