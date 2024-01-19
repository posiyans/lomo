<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-sm">
      <FilterBlock v-model="listQuery" />
      <div>
        <q-btn label="Excel" color="primary" @click="alert('ой')" />
      </div>
      <q-space />
    </div>
    <ShowTable :list="list" class="q-pt-sm" />
    <LoadMore v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import ShowTable from './components/ShowTable/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FiltersBlock/index.vue'
import { getBalanceList } from 'src/Modules/Bookkeeping/api/balaceApi'

export default defineComponent({
  components: {
    ShowTable,
    FilterBlock,
    LoadMore
  },
  props: {},
  setup(props, { emit }) {
    const key = ref(1)
    const list = ref([])
    const func = getBalanceList
    const listQuery = ref({
      find: '',
      rate_group_id: '',
      duty: '',
      zero_line: 0,
      page: 1,
      limit: 20
    })
    const setList = (val) => {
      list.value = val
    }
    return {
      listQuery,
      key,
      setList,
      func,
      list
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
