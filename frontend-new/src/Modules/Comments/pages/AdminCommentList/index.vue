<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <FilterBlock v-model="listQuery" />
        <CommentsTable :list="list" @reload="reload" />
        <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { useCommentsList } from 'src/Modules/Comments/hooks/useCommentsList'
import { getAllMessage } from 'src/Modules/Comments/api/commentApi'
import LoadMore from 'src/components/LoadMore/index.vue'
import CommentsTable from './components/CommentsTable/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'

export default defineComponent({
  components: {
    LoadMore,
    FilterBlock,
    CommentsTable
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const func = getAllMessage
    const { list, listQuery } = useCommentsList()
    const setList = (val) => {
      list.value = val
    }
    const reload = () => {
      key.value++
    }
    return {
      list,
      key,
      func,
      listQuery,
      setList,
      reload
    }
  }
})
</script>

<style scoped>

</style>
