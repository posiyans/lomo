import { ref } from 'vue'
import { getMessage } from 'src/Modules/Comments/api/commentApi'

export function useMessage() {
  const countMessage = ref(0)
  const getCountMessage = (objectType, objectUid) => {
    const data = {
      type: objectType,
      uid: objectUid,
      count: 1
    }
    getMessage(data)
      .then(res => {
        countMessage.value = res.data.data.count
      })
  }
  return {
    countMessage,
    getCountMessage
  }
}
