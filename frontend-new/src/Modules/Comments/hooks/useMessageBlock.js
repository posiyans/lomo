import { computed, onMounted, ref, watch } from 'vue'
import { checkMyBan } from 'src/Modules/BanUsers/api/apiBanUser'
import { getMessage, sendMessage } from 'src/Modules/Comments/api/commentApi'
import { useQuasar } from 'quasar'

export function useMessageBlock(type, prentUid) {
  const objectType = type
  const objectUid = prentUid
  const $q = useQuasar()

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
  const messageList = ref([])
  const messageLoading = ref(false)
  const showCount = ref(4)
  const showAll = ref(false)
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
    if (newMessage.value.message) {
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
            console.log(response.data.uid)
            newMessage.value.file.parent.uid = response.data.uid
            console.log(newMessage.value.file)
            console.log(newMessage.value.file.parent.uid)
            await newMessage.value.file.sendFileToServer()
          }
          newMessage.value.file = null
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
    messageLoading,
    ban,
    replyMessage,
    showAllMessage,
    sendComment,
    showCount,
    newMessage,
    uploadMessage

  }
}
