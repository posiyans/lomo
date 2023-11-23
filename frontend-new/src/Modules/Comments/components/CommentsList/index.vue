<template>
  <div v-if="show">
    <CommentsTable v-if="commentsList" :comments-list="commentsList" />
    <LoadMore :key="key" v-model:list-query="commentsList.listQuery.value" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useCommentsList } from 'src/Modules/Comments/hooks/useCommentsList'
import { getAllMessage } from 'src/Modules/Comments/api/commentApi'
import LoadMore from 'src/components/LoadMore/index.vue'
import CommentsTable from './components/CommentsTable/index.vue'

export default defineComponent({
  components: {
    LoadMore,
    CommentsTable
  },
  props: {
    userUid: {
      type: [String, Boolean],
      default: false
    }
  },
  setup(props, { emit }) {
    const key = ref(1)
    const show = ref(false)
    const func = getAllMessage
    const commentsList = useCommentsList()
    const setList = (val) => {
      commentsList.list.value = val
    }
    onMounted(() => {
      commentsList.listQuery.value.user_uid = props.userUid
      show.value = true
    })
    return {
      key,
      func,
      setList,
      show,
      commentsList
    }
  }
})
</script>

<style scoped>

</style>
