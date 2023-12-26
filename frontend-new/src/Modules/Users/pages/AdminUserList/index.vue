<template>
  <div class="q-pa-md">
    <FilterBlock v-model="listQuery" />
    <TableBlock :list="list" />
    <LoadMore v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import { fetchList } from 'src/Modules/Users/api/user-admin-api.js'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FiltersBlock/index.vue'
import TableBlock from './components/TableBlock/index.vue'

export default defineComponent({
  name: 'AdminUserList',
  components: {
    LoadMore,
    TableBlock,
    FilterBlock
  },
  props: {},
  setup(props, { emit }) {
    const list = ref([])
    const listQuery = ref({
      page: 1,
      limit: 20,
      find: ''
    })
    const func = fetchList
    const setList = (val) => {
      list.value = val
    }
    return {
      func,
      list,
      setList,
      listQuery
    }
  }
})
</script>

<style scoped>

</style>
