import { ref } from 'vue'

export function useCommentsList() {
  const list = ref([])

  const listQuery = ref(
    {
      key: 1,
      limit: 20,
      page: 1,
      user_uid: null,
      sortBy: 'updated_at',
      descending: false
    }
  )
  return {
    list,
    listQuery
  }
}
