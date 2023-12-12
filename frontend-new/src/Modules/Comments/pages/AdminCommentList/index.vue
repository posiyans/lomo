<template>
  <div>
    <CommentsTable v-if="commentsList" :comments-list="commentsList" @reload="reload" />
    <LoadMore :key="key" v-model:list-query="commentsList.listQuery.value" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useCommentsList } from 'src/Modules/Comments/hooks/useCommentsList'
import { getAllMessage } from 'src/Modules/Comments/api/commentApi'
import LoadMore from 'src/components/LoadMore/index.vue'
import CommentsTable from './components/CommentsTable/index.vue'

export default defineComponent({
  components: {
    LoadMore,
    CommentsTable
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const func = getAllMessage
    const commentsList = useCommentsList()
    const setList = (val) => {
      commentsList.list.value = val
    }
    const reload = () => {
      key.value++
    }
    return {
      key,
      func,
      setList,
      reload,
      commentsList
    }
  }
})
</script>

<style scoped>

</style>
