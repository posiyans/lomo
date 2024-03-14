import { computed, onMounted, ref, watch } from 'vue'
import { checkMyBan } from 'src/Modules/BanUsers/api/apiBanUser'
import { getMessage, sendMessage } from 'src/Modules/Comments/api/commentApi'
import { useQuasar } from 'quasar'
import { useAuthStore } from 'src/Modules/Auth/store/useAuthStore'

export function useMessageBlock(type, prentUid) {
  const objectType = type
  const objectUid = prentUid
  const $q = useQuasar()
  const messageList = ref([])
  const messageLoading = ref(false)
  const showCount = ref(4)
  const showAll = ref(false)

  const authStore = useAuthStore()
  const key = ref(1)
  const ban = ref({
    status: true,
    errorMessage: null,
    loading: true
  })
  onMounted(() => {
    getStatusBan()
    getData()
  })
  const getStatusBan = () => {
    if (authStore.is_guest) {
      ban.value.status = true
      ban.value.errorMessage = 'Чтоб оставлять комментарии необходимо войти на сайт'
      ban.value.loading = false
    } else {
      ban.value.loading = true
      const data = {
        type: objectType,
        uid: objectUid
      }
      checkMyBan(data)
        .then(res => {
          ban.value.status = res.data.ban
          ban.value.errorMessage = res.data.errorMessage
          ban.value.loading = false
        })
    }
  }

  const showMessage = computed(() => {
    // return messageList.value
    return messageList.value.slice(0, showCount.value)
  })
  const nextMessage = () => {
    showCount.value = showCount.value + 3
  }
  const getData = () => {
    messageLoading.value = true
    const data = {
      type: objectType,
      uid: objectUid
    }
    getMessage(data)
      .then(res => {
        messageList.value = res.data.data
        if (showAll.value) {
          showCount.value = messageList.value.length
          setTimeout(() => {
            window.scrollTo(0, document.body.scrollHeight)
          }, 250)
        }
      })
      .finally(() => {
        messageLoading.value = false
      })
  }
  watch(
    () => key.value,
    () => {
      getData()
    }
  )
  const newMessage = ref({
    reply: false,
    message: '',
    files: []
  })
  const messageSend = computed(() => {
    return newMessage.value.message.length > 0 || newMessage.value.files.length > 0
  })
  const replyMessage = (item) => {
    newMessage.value.reply = item
    window.scrollTo(0, document.body.scrollHeight)
  }

  const showAllMessage = () => {
    showAll.value = true
    showCount.value = messageList.value.length
  }
  const deleteReply = () => {
    newMessage.value.reply = null
  }
  const uploadMessage = ref(false)

  const sendComment = () => {
    if (messageSend.value) {
      uploadMessage.value = true
      const data = {
        type: objectType,
        uid: objectUid,
        message: newMessage.value.message,
        reply: newMessage.value.reply?.uid || ''
      }
      sendMessage(data)
        .then(async response => {
          if (newMessage.value.files.length > 0) {
            for (const file of newMessage.value.files) {
              if (!file.delete) {
                file.parent.uid = response.data.uid
                await file.sendFileToServer()
              }
            }
          }
          newMessage.value.files = []
          newMessage.value.message = ''
          newMessage.value.reply = null
          getData()
        })
        .catch(er => {
          getStatusBan()
          $q.notify(
            {
              message: er.response.data.errors,
              type: 'negative',
              position: 'top-right'
            }
          )
        })
        .finally(() => {
          uploadMessage.value = false
          showAll.value = true
          // getData()
          // props.messageBlock.reload++
          // emit('reload')
        })
    }
  }

  return {
    objectUid,
    objectType,
    key,
    deleteReply,
    messageList,
    showMessage,
    nextMessage,
    messageSend,
    messageLoading,
    ban,
    getStatusBan,
    replyMessage,
    showAllMessage,
    sendComment,
    showCount,
    newMessage,
    uploadMessage

  }
}
