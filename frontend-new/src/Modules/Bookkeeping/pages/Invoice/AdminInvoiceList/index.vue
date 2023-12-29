<template>
  <div class="q-pa-md">
    <div class="row items-center q-col-gutter-sm">
      <FilterBlock v-model="listQuery" />
      <div>
        <q-btn label="Скачать" color="primary" />
      </div>
    </div>
    <ShowTable :list="list" @reload="reload" class="q-pt-sm" />
    <LoadMore :key="key" v-model:list-query="listQuery" :func="func" @setList="setList" />
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import ShowTable from './components/ShowTable/index.vue'
import LoadMore from 'src/components/LoadMore/index.vue'
import FilterBlock from './components/FilterBlock/index.vue'
import { getInvoiceList } from 'src/Modules/Bookkeeping/api/invoiceAdminApi'

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
    const func = getInvoiceList
    const listQuery = ref({
      stead_id: '',
      is_paid: 0,
      rate_group_id: '',
      page: 1,
      limit: 20
    })
    const reload = () => {
      key.value++
    }
    const setList = (val) => {
      list.value = val
    }
    return {
      listQuery,
      key,
      reload,
      setList,
      func,
      list
    }
  }
})
</script>

<style scoped lang='scss'>

</style>
